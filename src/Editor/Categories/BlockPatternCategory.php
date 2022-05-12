<?php

namespace Webaxones\Core\Editor\Categories;

defined( 'ABSPATH' ) || exit;

use \DecaLog\Engine as Decalog;

/**
 * Custom block pattern category declaration
 */
class BlockPatternCategory extends AbstractEditorCategory
{
	/**
	 * Get action to execute on block pattern category
	 *
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * Check if editor category already exists
	 *
	 * @return bool
	 */
	public function editorCategoryAlreadyExists(): bool
	{
		return \WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $this->slug );
	}

	/**
	 * Add editor category
	 *
	 * @return void
	 */
	public function addEditorCategory(): void
	{
		register_block_pattern_category( $this->slug, $this->args );
	}

	/**
	 * Remove editor category
	 *
	 * @return void
	 */
	public function removeEditorCategory(): void
	{
		unregister_block_pattern_category( $this->slug );
	}

	/**
	 * Process block pattern category
	 *
	 * @return array
	 */
	public function processCategory(): void
	{
		if ( 'add' === $this->getAction() && ! $this->editorCategoryAlreadyExists() ) {
			$this->addEditorCategory();
			DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' » Block pattern category registered.' );
		}

		if ( 'remove' === $this->getAction() && $this->editorCategoryAlreadyExists() ) {
			$this->removeEditorCategory();
			DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' » Block pattern category unregistered.' );
		}
	}
}
