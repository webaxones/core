<?php

namespace Webaxones\Core\Entities;

defined( 'ABSPATH' ) || exit;

use Exception;
use \Decalog\Engine as Decalog;
use Webaxones\Core\Entities\Entity;
use Webaxones\Core\Hook\Hook;

/**
 * Entities processing
 */
final class Entities
{
	public static function process( array $declarations ): void
	{
		DecaLog::initLibrary( 'webaxones-entities', 'Webaxones Entities Library', '1.0.0' );
		$logger = DecaLog::eventsLogger( 'webaxones-entities' );

		foreach ( $declarations as $declaration ) {
			try {
				$entity       = new Entity( $declaration );
				$entityHandle = $entity->createEntity();
				$hook         = new Hook();
				$hook->register( $entityHandle );
			} catch ( Exception $e ) {
				$logger->info( 'Unable to create entity. ' . $e->getMessage() );
			}
		}
	}
}
