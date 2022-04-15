<?php

namespace Webaxones\Core\I18n;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

/**
 * Labels internationalization
 */
class I18n implements HookInterface, ActionInterface
{
	/**
	 * Text domain
	 *
	 * @var string
	 */
	protected string $textDomain;

	/**
	 * I18n class constructor
	 *
	 * @param  string $textDomain
	 */
	public function __construct( $textDomain )
	{
		$this->textDomain = $textDomain;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [ $this->getHookName() => [ 'loadPluginTextDomain', 10, 1 ] ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'init';
	}

	/**
	 * Get text domain
	 *
	 * @return string text domain
	 */
	public function getTextDomain()
	{
		return $this->textDomain;
	}

	/**
	 * Load Plugin Text Domain
	 *
	 * @return array
	 */
	public function loadPluginTextDomain(): void
	{
		load_plugin_textdomain( $this->getTextDomain(), false, plugin_dir_path( __FILE__ ) . 'languages' );
	}
}
