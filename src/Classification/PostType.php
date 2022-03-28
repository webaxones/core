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
	public function registerCustomDeclarations(): void
	{
		register_post_type( $this->slug, $this->args );
	}
}
