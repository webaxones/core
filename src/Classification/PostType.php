<?php

namespace Webaxones\Core\Classification;

defined( 'ABSPATH' ) || exit;

/**
 * Custom post type declaration
 */
class PostType extends AbstractClassification
{
	/**
	 * Final process callback function
	 *
	 * @return array
	 */
	public function finalProcess(): void
	{
		register_post_type( $this->slug, $this->args );
	}
}
