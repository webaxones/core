<?php

namespace Webaxones\Core\I18n;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Utils\Contracts\HooksInterface;

/**
 * Labels internationalization
 */
class I18n implements HooksInterface
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
	public function hook(): void
	{
		add_action( $this->getHookName(), [ $this, 'finalProcess' ] );
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
	 * {@inheritdoc}
	 */
	public function finalProcess(): void
	{
		load_plugin_textdomain( $this->getTextDomain(), false, plugin_dir_path( __FILE__ ) . 'languages' );
	}
}
