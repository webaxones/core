<?php

namespace Webaxones\Core\Option;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Utils\Contracts\PhpToJsInterface;

use \Decalog\Engine as Decalog;

/**
 * Custom native option page declaration
 */
class OptionsPage extends AbstractOptionsPage implements PhpToJsInterface
{
	/**
	 * Options page declaration arguments
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'admin_menu';
	}

	/**
	 * Set options page arguments
	 *
	 * @return void
	 */
	public function setArgs(): void
	{
		$this->args = [
			$this->labels->getLabel( 'page_title' ),
			$this->labels->getLabel( 'menu_title' ),
			$this->settings['capability'],
			$this->settings['slug'],
			[ $this, 'optionsPageContent' ],
		];
		if ( 'add_menu_page' === $this->getAddPageFunction() ) {
			$this->args[] = $this->settings['icon_url'];
		}
		$this->args[] = $this->settings['position'];
	}

	/**
	 * Get options page arguments
	 *
	 * @return array
	 */
	public function getArgs(): array
	{
		return $this->args;
	}

	/**
	 * Display options page content
	 *
	 * @return void
	 */
	public function optionsPageContent(): void
	{
		printf(
			'<div class="wrap"><h1>%s</h1><div id="%s"></div></div>',
			esc_html( $this->labels->getLabel( 'page_title' ) ),
			esc_attr( $this->getSlug() . '__content' ),
		);
	}

	/**
	 * Add options page
	 *
	 * @return void
	 */
	public function addOptionsPage(): void
	{
		$this->setArgs();
		$this->getAddPageFunction()( ...$this->getArgs() );
		DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->slug . ' » Options Page added.' );
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
		return 'let ' . $this->getSlug() . ' = ' . wp_json_encode( $data );
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareData(): array
	{
		return [];
	}
}
