<?php

namespace Webaxones\Core\Option;

defined( 'ABSPATH' ) || exit;

/**
 * Custom ACF option page declaration
 */
class AcfOptionsPage extends AbstractOptionsPage
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
			'page_title'  => $this->labels->getLabel( 'page_title' ),
			'menu_title'  => $this->labels->getLabel( 'menu_title' ),
			'menu_slug'   => $this->settings['slug'],
			'capability'  => $this->settings['capability'],
			'position'    => $this->settings['position'],
			'parent_slug' => $this->settings['parent_slug'],
			'icon_url'    => $this->settings['icon_url'],
		];
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
	 * {@inheritdoc}
	 */
	public function finalProcess(): void
	{
		$this->setArgs();

		acf_add_options_page( $this->getArgs() );
	}
}
