<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface AssetInterface
{
	/**
	 * Check if "cache" directory exists in WP "content" directory and if not, create it
	 *
	 * @return string WordPress cache directory
	 */
	public function checkCacheDirectory(): string;

	/**
	 * Check if webaxones assets directory exists in WP cache directory
	 * and if not copy it from library vendor directory
	 *
	 * @param  string $wpCacheDirectory WordPress cache directory
	 *
	 * @return void
	 */
	public function checkAssetsDirectory( string $wpCacheDirectory ): void;

	/**
	 * Copy webaxones assets directory from vendor to WP content cache
	 *
	 * @param  string $vendorAssetsDirectory library "assets" directory in "vendor"
	 * @param  string $webaxonesAssetsDirectory library "assets" directory in "cache/webaxones/"
	 *
	 * @return void
	 */
	public function copyAssetsDirectory( string $vendorAssetsDirectory, string $webaxonesAssetsDirectory ): void;

	/**
	 * Enqueue asset
	 *
	 * @return void
	 */
	public function enqueueAsset(): void;

}
