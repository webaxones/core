<?php

namespace Webaxones\Core\Option;

defined( 'ABSPATH' ) || exit;

/**
 * Custom native option page declaration
 */
class OptionsPage extends AbstractOptionsPage
{
	/**
	 * Options page declaration arguments
	 *
	 * @var array
	 */
	protected array $args;

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
	 * Get options page menu slug
	 *
	 * @return string
	 */
	public function getSlug(): string
	{
		return $this->settings['menu_slug'];
	}

	/**
	 * Display options page content
	 *
	 * @return void
	 */
	public function optionsPageContent(): void
	{
		printf(
			'<div id="%s"></div>',
			esc_attr( $this->getSlug() . '__content' ),
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function finalProcess(): void
	{
		$this->setArgs();

		$this->getAddPageFunction()( ...$this->getArgs() );
	}
}
