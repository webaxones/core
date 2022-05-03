<?php
namespace Webaxones\Core\Admin;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Exception;

/**
 * Manage Asset
 */
abstract class AbstractAsset implements HookInterface, ActionInterface
{
	/**
	 * {@inheritdoc}
	 */
	abstract public function getHookName(): string;

	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [ $this->getHookName() => [ 'enqueueAsset', 10, 1 ] ];
	}

	/**
	 * Check if cache directory exists in WP content directory and if not create it
	 *
	 * @return string
	 */
	public function checkCacheDirectory(): string
	{
		$wpCacheDirectory = wp_normalize_path( WP_CONTENT_DIR . '/cache' );
		if ( ! is_dir( $wpCacheDirectory ) ) {
			global $wp_filesystem;
			WP_Filesystem();
			$wp_filesystem->mkdir( $wpCacheDirectory );
		}
		return $wpCacheDirectory;
	}

	/**
	 * Check if webaxones assets directory exists in WP cache directory and if not copy it from vendor
	 *
	 * @param  string $wpCacheDirectory
	 *
	 * @return void
	 */
	public function checkAssetsDirectory( string $wpCacheDirectory ): void
	{
		$webaxonesAssetsDirectory = $wpCacheDirectory . '/webaxones/assets';
		$vendorAssetsDirectory    = wp_normalize_path( WEBAXONES_VENDOR_PATH . 'webaxones/core/src/assets' );

		if ( is_dir( $vendorAssetsDirectory ) && ! is_dir( $webaxonesAssetsDirectory ) ) {
			global $wp_filesystem;
			WP_Filesystem();
			$wp_filesystem->mkdir( $wpCacheDirectory . '/webaxones' );
			$wp_filesystem->mkdir( $webaxonesAssetsDirectory );

			$this->copyAssetsDirectory( $vendorAssetsDirectory, $webaxonesAssetsDirectory );
		}
	}

	/**
	 * Copy webaxones assets directory from vendor to WP content cache
	 *
	 * @param  string $vendorAssetsDirectory
	 * @param  string $webaxonesAssetsDirectory
	 *
	 * @return void
	 */
	public function copyAssetsDirectory( string $vendorAssetsDirectory, string $webaxonesAssetsDirectory ): void
	{
		WP_Filesystem();
		copy_dir( $vendorAssetsDirectory, $webaxonesAssetsDirectory );
	}

	/**
	 * Check if assets folder is in cache folder and if not, copy it.
	 *
	 * @return void
	 */
	public function checkAndPrepareAssets(): void
	{
		$wpCacheDirectory = $this->checkCacheDirectory();

		$this->checkAssetsDirectory( $wpCacheDirectory );
	}

	/**
	 * Enqueue asset
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	abstract public function enqueueAsset(): void;
}
