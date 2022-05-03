<?php

namespace Webaxones\Core\Option;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;
use Webaxones\Core\Utils\Contracts\SettingInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Label\Labels;
use \Decalog\Engine as Decalog;

/**
 * Custom Setting declaration
 */
class Setting implements EntityInterface, HookInterface, ActionInterface, SettingInterface
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
	 * Option page slug
	 *
	 * @var string
	 */
	protected string $pageSlug;

	/**
	 * Setting slug
	 *
	 * @var string
	 */
	protected string $slug;

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
			'wp_print_scripts'   => [ 'sendSettingToJS', 10, 1 ],
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
	public function getPageSlug(): string
	{
		return $this->pageSlug;
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
	public function registerSetting(): void
	{
		register_setting(
			$this->getPageSlug(),
			$this->getSlug(),
			[
				'default'      => '',
				'show_in_rest' => true,
			]
		);
		DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $field['slug'] . ' » Settings field of « ' . $this->getSlug() . ' » Settings group registered.' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendSettingToJS(): void
	{
		wp_add_inline_script( 'webaxones-core', $this->stringifySetting( $this->prepareSetting() ), 'before' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function stringifySetting( array $setting ): string
	{
		return 'const setting = ' . wp_json_encode( $setting );
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareSetting(): array
	{
		return array_merge( $this->args['labels'], $this->getSettings() );
	}
}
