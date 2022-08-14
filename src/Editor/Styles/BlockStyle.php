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
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'init';
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
				Decalog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' / ' . $this->getStyleName() . ' » Block style already exist.' );
				return;
			}
			$this->addBlockStyle();
			Decalog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' / ' . $this->getStyleName() . ' » Block style registered.' );
		}

		if ( 'remove' === $this->getAction() ) {
			if ( ! $this->blockStyleAlreadyExists() ) {
				Decalog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' / ' . $this->getStyleName() . ' » Block style doesn’t exist.' );
				return;
			}
			if ( array_key_exists( 'label', $this->getArgs() ) ) {
				$args = $this->getArgs();
				unset( $args['label'] );
				$this->args = $args;
			}
			$this->removeBlockStyle();
			Decalog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' / ' . $this->getStyleName() . ' » Block style unregistered.' );
		}
	}
}
