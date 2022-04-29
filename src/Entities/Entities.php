<?php

namespace Webaxones\Core\Entities;

defined( 'ABSPATH' ) || exit;

use Exception;
use \Decalog\Engine as Decalog;
use Webaxones\Core\Entities\Entity;
use Webaxones\Core\Hook\Hook;
use Webaxones\Core\Admin\Asset;

/**
 * Entities processing
 */
final class Entities
{
	public static function process( array $declarations ): void
	{

		$asset = new Asset();
		$hook  = new Hook();
		$hook->register( $asset );

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
