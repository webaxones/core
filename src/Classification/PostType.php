<?php

namespace Webaxones\Core\Classification;

defined( 'ABSPATH' ) || exit;

use \Decalog\Engine as Decalog;

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
		DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->slug . ' » Custom Post Type registered.' );
	}
}
