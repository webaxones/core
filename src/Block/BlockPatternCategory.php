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
	public function finalProcess(): void
	{
		register_block_pattern_category( $this->slug, $this->args['labels'] );
	}
}
