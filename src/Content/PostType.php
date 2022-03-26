<?php

namespace Webaxones\Core\Content;

defined( 'ABSPATH' ) || exit;

/**
 * Custom post type declaration
 */
class PostType extends AbstractContent
{
	/**
	 * {@inheritdoc}
	 */
	public function registerCustomDeclarations(): void
	{
		register_post_type( $this->slug, $this->args );
	}
}
