<?php

namespace Webaxones\Core\Entities;

defined( 'ABSPATH' ) || exit;

use Exception;
use \DecaLog\Engine as Decalog;
use Webaxones\Core\Entities\Entity;
use Webaxones\Core\Hook\Hook;
use Webaxones\Core\Asset\LibraryAsset;

/**
 * Entities processing
 */
final class Entities
{
	public static function process( array $declarations ): void
	{
		$pageSlug = isset( $_GET['page'] ) ? $_GET['page'] : '';
		foreach ( $declarations as $declaration ) {
			$libraryAssetRegistered = false;
			if ( is_admin() && ! $libraryAssetRegistered && 'Webaxones\Core\Option\SettingGroup' === $declaration['entity'] && $pageSlug === $declaration['settings']['page_slug'] ) {
				$script = new LibraryAsset();
				$hook   = new Hook();
				$hook->register( $script );
				$libraryAssetRegistered = true;
			}

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
