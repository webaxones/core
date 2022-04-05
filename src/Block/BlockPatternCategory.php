<?php

namespace Webaxones\Core\Block;

defined( 'ABSPATH' ) || exit;

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
	 * Final process callback function
	 *
	 * @return array
	 */
	public function finalProcess(): void
	{
		if ( 'add' === $this->getAction() && ! $this->editorCategoryAlreadyExists() ) {
			$this->addEditorCategory();
		}

		if ( 'remove' === $this->getAction() && $this->editorCategoryAlreadyExists() ) {
			$this->removeEditorCategory();
		}
	}
}