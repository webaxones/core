<?php

namespace Webaxones\Core\Option;

defined( 'ABSPATH' ) || exit;

use Composer\Installers\PortoInstaller;
use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;
use Webaxones\Core\Utils\Contracts\SettingGroupInterface;
use Webaxones\Core\Utils\Contracts\PhpToJsInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Label\Labels;


/**
 * Custom Setting group declaration
 */
class SettingGroup implements EntityInterface, HookInterface, ActionInterface, SettingGroupInterface, PhpToJsInterface
{
	use ClassNameTrait;

	/**
	 * Input settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Input labels
	 *
	 * @var object
	 */
	protected object $labels;

	/**
	 * Output args
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * Slug
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Page slug
	 *
	 * @var string
	 */
	protected string $pageSlug;

	/**
	 * Settings group fields
	 *
	 * @var array
	 */
	protected array $fields;

	public function __construct( array $parameters, Labels $labels )
	{
		if ( ! array_key_exists( 'settings', $parameters ) || empty( $parameters['settings'] ) ) {
			throw new Exception( 'Settings missing in content ' . $this->getCurrentClassShortName() . ' declaration' );
		}

		$this->settings       = $parameters['settings'];
		$this->labels         = $labels;
		$this->args['labels'] = $this->labels->processLabels();
		$this->slug           = $this->sanitizeSlug();
		$this->pageSlug       = $parameters['settings']['page_slug'];
		$this->fields         = $this->settings['fields'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'admin_init';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInlineScriptHookName(): string
	{
		return 'wp_print_scripts';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [
			$this->getHookName()             => [ 'registerSetting', 10, 1 ],
			'rest_api_init'                  => [ 'registerSetting', 10, 1 ],
			$this->getInlineScriptHookName() => [ 'sendDataToJS', 10, 1 ],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSettings(): array
	{
		return $this->settings;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSlug(): string
	{
		return $this->slug;
	}

	/**
	 * {@inheritdoc}
	 */
	public function sanitizeSlug(): string
	{
		$settings = $this->getSettings();
		return sanitize_title( $settings['slug'] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPageSlug(): string
	{
		return $this->pageSlug;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFields(): array
	{
		return $this->fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAllChildren(): array
	{
		$allChildren = [];
		$fields      = $this->getFields();
		array_walk(
			$fields,
			function( $field ) use( &$allChildren ) {
				if ( array_key_exists( 'children', $field ) ) {
					foreach ( $field['children'] as $child ) {
						array_push( $allChildren, $child );
					}
				}
			}
		);
		return $allChildren;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setArgsFromSettingType( array $field ): array
	{
		$type = 'string';

		if ( in_array( $field['type'], ['checkbox', 'toggle'], true ) ) {
			$type = 'boolean';
		}

		$args = [
			'type'         => $type,
			'default'      => '',
			'show_in_rest' => true,
		];

		if ( 'image' === $field['type'] ) {
			$args = [
				'type'         => 'object',
				'default'      => [
					'id'  => 0,
					'url' => '',
				],
				'show_in_rest' => [
					'schema' => [
						'type'       => 'object',
						'properties' => [
							'id'  => [
								'type' => 'integer',
							],
							'url' => [
								'type' => 'string',
							],
						],
					],
				],
			];
		}

		if ( ( 'selectData' === $field['type'] && false === $field['args']['is_multiple'] )
			|| ( 'selectDataScroll' === $field['type'] && false === $field['args']['is_multiple'] ) ) {
			$args = [
				'type'         => 'object',
				'default'      => [
					'value' => 0,
					'label' => '',
				],
				'show_in_rest' => [
					'schema' => [
						'type'       => 'object',
						'properties' => [
							'value' => [
								'type' => 'integer',
							],
							'label' => [
								'type' => 'string',
							],
						],
					],
				],
			];
		}

		if ( ( 'selectData' === $field['type'] && true === $field['args']['is_multiple'] )
			|| ( 'selectDataScroll' === $field['type'] && true === $field['args']['is_multiple'] ) ) {
			$args = [
				'type'         => 'array',
				'show_in_rest' => [
					'schema' => [
						'items' => [
							'type'       => 'object',
							'properties' => [
								'value' => [
									'type' => 'integer',
								],
								'label' => [
									'type' => 'string',
								],
							],
						],
					],
				],
			];
		}

		if ( in_array( $field['type'], ['section'], true ) ) {
			$args = [
				'type'         => 'array',
				'show_in_rest' => [
					'schema' => [
						'items' => [
							'type'       => 'object',
							'properties' => [
								'slug'     => [
									'type' => 'string',
								],
								'value' => [
									'type' => ['string', 'number', 'integer', 'boolean', 'array', 'object', 'null'],
								],
							],
						],
					],
				],
			];
		}

		if ( in_array( $field['type'], ['repeater'], true ) ) {
			$args = [
				'type'         => 'array',
				'show_in_rest' => [
					'schema' => [
						'items' => [
							'type'       => 'object',
							'properties' => [
								'slug'  => [
									'type' => 'string',
								],
								'value' => [
									'type' => ['string', 'number', 'integer', 'boolean', 'array', 'object', 'null'],
								],
							],
						],
					],
				],
			];
		}

		return $args;
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerSetting(): void
	{
		$allFields = $this->getFields();
		array_walk(
			$allFields,
			function( $field ) {
				$args = $this->setArgsFromSettingType( $field );

				register_setting(
					$this->getSlug(),
					$field['slug'],
					$args
				);
			}
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendDataToJS(): void
	{
		wp_add_inline_script( 'webaxones-core', $this->stringifyData( $this->prepareData() ), 'before' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function stringifyData( array $data ): string
	{
		return 'webaxonesApps.push(' . wp_json_encode( $data ) . ')';
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareLabels( array $data ): array
	{
		$outputData = $data;
		array_walk_recursive(
			$outputData,
			function ( &$item, $key )
			{

				if ( 'label' === $key || ( 'help' === $key && '' !== $item ) ) {
					$item = $this->args['labels'][ $item ];
				}
			}
		);
		return $outputData;
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareGroups( array $data ): array
	{
		$outputData = $data;
		array_walk(
			$outputData['fields'],
			function ( &$item, $key )
			use( $data )
			{
				$item['tab']      = $data['slug'];
				$item['tab_name'] = $data['label'];
				$item['page']     = $data['page_slug'];

				foreach ( $item['labels'] as $labelKey => $labelValue ) {
					$item[ $labelKey ] = $item['labels'][ $labelKey ];
				}
				unset( $item['labels'] );
				if ( array_key_exists( 'children', $item ) ) {
					foreach ( $item['children'] as &$child ) {
						$child['tab']  = $data['slug'];
						$child['page'] = $data['page_slug'];
						foreach ( $child['labels'] as $labelKey => $labelValue ) {
							$child[ $labelKey ] = $child['labels'][ $labelKey ];
						}
						unset( $child['labels'] );
					}
				}
			}
		);
		return $outputData['fields'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareData(): array
	{
		$data = array_merge( $this->args['labels'], $this->getSettings() );
		$data = $this->prepareLabels( $data );
		$data = $this->prepareGroups( $data );
		return $data;
	}
}
