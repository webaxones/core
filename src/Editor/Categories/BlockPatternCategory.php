<?php

namespace Webaxones\Core\Editor\Categories;

defined( 'ABSPATH' ) || exit;



/**
 * Handles creating, deleting custom Block pattern category
 */
class BlockPatternCategory extends AbstractEditorCategory
{
	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [ $this->getHookName() => [ 'processBlockPatternCategory', 10 ] ];
	}

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
		if ( ! $this->editorCategoryAlreadyExists() ) {
			register_block_pattern_category( $this->slug, $this->args );
		}
	}

	/**
	 * Remove editor category
	 *
	 * @return void
	 */
	public function removeEditorCategory(): void
	{
		if ( $this->editorCategoryAlreadyExists() ) {
			unregister_block_pattern_category( $this->slug );
		}
	}


	/**
	 * Process block pattern category
	 *
	 * @return array
	 */
	public function processBlockPatternCategory(): void
	{
		if ( 'add' === $this->getAction() ) {
			$this->args = array_merge(
				$this->args,
				[
					'label' => $this->args['label'],
				],
			);

			$this->addEditorCategory();
		}

		if ( 'remove' === $this->getAction() ) {
			$this->removeEditorCategory();
		}
	}
}
