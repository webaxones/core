<?php

namespace Webaxones\Core\Option;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;
use Webaxones\Core\Utils\Contracts\SettingBlockInterface;
use Webaxones\Core\Utils\Contracts\PhpToJsInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Label\Labels;
use \Decalog\Engine as Decalog;

/**
 * Custom Setting block declaration
 */
class SettingBlock implements EntityInterface, HookInterface, ActionInterface, SettingBlockInterface, PhpToJsInterface
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
	 * Settings block fields
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
		$this->fields         = $this->settings['fields'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'wp_enqueue_scripts';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [
			$this->getHookName() => [ 'registerSetting', 10, 1 ],
			'wp_print_scripts'   => [ 'sendDataToJS', 10, 1 ],
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
	public function getFields(): array
	{
		return $this->fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerSetting(): void
	{
		$fields = $this->getFields();
		array_walk(
			$fields,
			function( $field ) {
				register_setting(
					$this->getSlug(),
					$field['slug'],
					[
						'default'      => '',
						'show_in_rest' => true,
					]
				);
				DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $field['slug'] . ' » Settings field of « ' . $this->getSlug() . ' » Settings block registered.' );
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
		return 'const settingsBlock = ' . wp_json_encode( $data );
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareData(): array
	{
		return array_merge( $this->args['labels'], $this->getSettings() );
	}
}
