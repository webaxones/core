<?php

namespace Webaxones\Core\Classification;

defined( 'ABSPATH' ) || exit;

/**
 * Custom post type declaration
 */
class PostType extends AbstractClassification
{
	/**
	 * {@inheritdoc}
	 */
	public function executeCustomDeclarations(): void
	{
		register_post_type( $this->slug, $this->args );
	}
}
