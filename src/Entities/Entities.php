<?php

namespace Webaxones\Core\Entities;

defined( 'ABSPATH' ) || exit;

use Exception;
use \DecaLog\Engine as Decalog;
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
				Decalog::eventsLogger( 'webaxones-entities' )->error( $e->getMessage() );
			}
		}
	}
}
