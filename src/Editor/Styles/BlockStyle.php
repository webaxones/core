<?php

namespace Webaxones\Core\Editor\Styles;

defined( 'ABSPATH' ) || exit;

use \DecaLog\Engine as Decalog;

/**
 * Custom block style declaration
 */
class BlockStyle extends AbstractEditorStyle
{
	/**
	 * StyleSheet URI
	 *
	 * @var string
	 * @throws
	 */
	protected string $styleSheetURI = '';

	/**
	 * {@inheritdoc}
	 */
	public function setArgs(): void
	{
		$args          = [];
		$args['name']  = $this->getStyleName();
		$labels        = $this->labels->processLabels();
		$args['label'] = $labels['label'];
		if ( array_key_exists( 'is_default', $this->settings ) ) {
			$args['is_default'] = $this->settings['is_default'];
		}
		if ( array_key_exists( 'inline_style', $this->settings ) ) {
			$args['inline_style'] = $this->settings['inline_style'];
		}
		if ( array_key_exists( 'style_handle', $this->settings ) ) {
			$args['style_handle'] = $this->settings['style_handle'];
		}
		if ( array_key_exists( 'style_uri', $this->settings ) ) {
			$this->styleSheetURI = $this->settings['style_uri'];
		}
		$this->args = $args;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'after_setup_theme';
	}

	/**
	 * Get action to execute on block style
	 *
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * Check if block style already exists
	 *
	 * @return bool
	 */
	public function blockStyleAlreadyExists(): bool
	{
		return \WP_Block_Styles_Registry::get_instance()->is_registered( $this->getSlug(), $this->getStyleName() );
	}

	/**
	 * Add block style
	 *
	 * @return void
	 */
	public function addBlockStyle(): void
	{
		if ( array_key_exists( 'style_handle', $this->getArgs() ) ) {
			wp_register_style( $this->args['style_handle'], $this->styleSheetURI, [], wp_get_theme()->get( 'Version' ) );
		}
		register_block_style( $this->getSlug(), $this->getArgs() );
	}

	/**
	 * Remove block style
	 *
	 * @return void
	 */
	public function removeBlockStyle(): void
	{

		unregister_block_style( $this->getSlug(), $this->getArgs() );
	}

	/**
	 * Process block style
	 *
	 * @return array
	 */
	public function processStyle(): void
	{
		if ( 'add' === $this->getAction() ) {
			if ( $this->blockStyleAlreadyExists() ) {
				Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' - ' . $this->getStyleName() . ' » Block style already exist.' );
				return;
			}
			$this->addBlockStyle();
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' - ' . $this->getStyleName() . ' » Block style registered.' );
		}

		if ( 'remove' === $this->getAction() ) {
			if ( ! $this->blockStyleAlreadyExists() ) {
				Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' - ' . $this->getStyleName() . ' » Block style doesn’t exist.' );
				return;
			}
			if ( array_key_exists( 'label', $this->getArgs() ) ) {
				$args = $this->getArgs();
				unset( $args['label'] );
				$this->args = $args;
			}
			$this->removeBlockStyle();
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' - ' . $this->getStyleName() . ' » Block style unregistered.' );
		}
	}
}
