<?php

namespace WaxCustom;

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
