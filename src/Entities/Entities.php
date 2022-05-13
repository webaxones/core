<?php

namespace Webaxones\Core\Entities;

defined( 'ABSPATH' ) || exit;

use Exception;
use \Decalog\Engine as Decalog;
use Webaxones\Core\Entities\Entity;
use Webaxones\Core\Hook\Hook;
use Webaxones\Core\Admin\AdminScript;

/**
 * Entities processing
 */
final class Entities
{
	public static function process( array $declarations ): void
	{
		$vendorPath = substr( __FILE__, 0, strpos( __FILE__, 'vendor\\' ) ) . 'vendor\\';
		define( 'WEBAXONES_VENDOR_PATH', $vendorPath );

		$script = new AdminScript();
		$hook   = new Hook();
		$hook->register( $script );

		foreach ( $declarations as $declaration ) {
			try {
				$entity       = new Entity( $declaration );
				$entityHandle = $entity->createEntity();
				$hook         = new Hook();
				$hook->register( $entityHandle );
			} catch ( Exception $e ) {
				DecaLog::eventsLogger( 'webaxones-entities' )->error( $e->getMessage() );
			}
		}
	}
}
