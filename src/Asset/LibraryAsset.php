<?php
namespace Webaxones\Core\Asset;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;
use Webaxones\Core\Utils\Contracts\PhpToJsInterface;
use Webaxones\Core\Utils\Contracts\AssetInterface;

/**
 * Manage Library Asset which is composed of 2 files: 1 JS file and 1 CSS file
 * Add Inline script to send PHP data to JS asset file
 */
class LibraryAsset implements hookInterface, ActionInterface, PhpToJsInterface, AssetInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'admin_enqueue_scripts';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInlineScriptHookName(): string
	{
		return 'wp_print_scripts';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [
			$this->getHookName()             => [ 'enqueueAsset', 10, 1 ],
			$this->getInlineScriptHookName() => [ 'sendDataToJS', 10, 1 ],
		];
	}

	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	public function copyAssetsDirectory( string $vendorAssetsDirectory, string $webaxonesAssetsDirectory ): void
	{
		WP_Filesystem();
		copy_dir( $vendorAssetsDirectory, $webaxonesAssetsDirectory );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws Exception
	 */
	public function enqueueAsset(): void
	{
		if ( function_exists( 'wp_enqueue_media' ) ) {
			// Required for the process to work
			wp_enqueue_media();
		}

		$wpCacheDirectory = $this->checkCacheDirectory();

		$this->checkAssetsDirectory( $wpCacheDirectory );

		$script_asset_path = WP_CONTENT_DIR . '\cache\webaxones\assets\js\index.asset.php';
		if ( ! file_exists( $script_asset_path ) ) {
			throw new Exception( '« ' . WP_CONTENT_DIR . '\cache\webaxones\assets\js\index.asset.php » doesn’t exist.' );
		}

    	$script_asset = require $script_asset_path;

		if ( ! wp_script_is( 'webaxones-core', 'enqueued' ) ) {
			wp_enqueue_script(
				'webaxones-core',
				content_url() . '/cache/webaxones/assets/js/index.js',
				$script_asset['dependencies'],
				$script_asset['version'],
				true
			);
		}

		foreach ( $script_asset['dependencies'] as $style ) {
			wp_enqueue_style( $style );
		}

		$admin_css = content_url() . '/cache/webaxones/assets/js/index.css';
		wp_enqueue_style(
			'webaxones-core-styles',
			$admin_css,
			['wp-components'],
			filemtime( WP_CONTENT_DIR . '\cache\webaxones\assets\js\index.css' )
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendDataToJS(): void
	{
		wp_add_inline_script( 'webaxones-core', $this->stringifyData( $this->prepareData() ), 'before' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function stringifyData( array $data ): string
	{
		if ( ! defined( 'WEBAXONES_APPS_DECLARED' ) ) {
			define( 'WEBAXONES_APPS_DECLARED', 'declared' );
			return 'let webaxonesApps = ' . wp_json_encode( $data );
		}
		return 'webaxonesApps = ' . wp_json_encode( $data );
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareLabels( array $data ): array
	{
		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareGroups( array $data ): array
	{
		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareData(): array
	{
		return [];
	}
}
