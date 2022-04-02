<?php

namespace Webaxones\Core\Block;

defined( 'ABSPATH' ) || exit;

/**
 * Custom block pattern declaration
 */
class BlockPatternCategory extends AbstractEditorCategory
{
	/**
	 * {@inheritdoc}
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockPatternCategoryAlreadyExists(): bool
	{
		return \WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $this->slug );
	}

	/**
	 * {@inheritdoc}
	 */
	public function addBlockPatternCategory(): void
	{
		register_block_pattern_category( $this->slug, $this->args['labels'] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeBlockPatternCategory(): void
	{
		unregister_block_pattern_category( $this->slug );
	}

	/**
	 * {@inheritdoc}
	 */
	public function finalProcess(): void
	{
		if ( 'add' === $this->getAction() && ! $this->blockPatternCategoryAlreadyExists() ) {
			$this->addBlockPatternCategory();
		}

		if ( 'remove' === $this->getAction() && $this->blockPatternCategoryAlreadyExists() ) {
			$this->removeBlockPatternCategory();
		}
	}
}
