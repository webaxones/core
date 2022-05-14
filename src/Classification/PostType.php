<?php

namespace Webaxones\Core\Classification;

defined( 'ABSPATH' ) || exit;

use \DecaLog\Engine as Decalog;

/**
 * Custom post type declaration
 */
class PostType extends AbstractClassification
{
	/**
	 * {@inheritdoc}
	 */
	public function registerClassification(): void
	{
		register_post_type( $this->slug, $this->args );
		Decalog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->slug . ' » Custom Post Type registered.' );
	}
}
