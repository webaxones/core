<?php
namespace Webaxones\Core\Admin;

defined( 'ABSPATH' ) || exit;

use Exception;

/**
 * Manage Script
 */
class Script extends AbstractAsset
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
	public function getActions(): array
	{
		return [ $this->getHookName() => [ 'enqueueAsset', 10, 1 ] ];
	}

	/**
	 * Enqueue script
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function enqueueAsset(): void
	{
		$this->checkAndPrepareAssets();

		$script_asset_path = WP_CONTENT_DIR . '\cache\webaxones\assets\js\index.asset.php';
		if ( ! file_exists( $script_asset_path ) ) {
			throw new Exception( '« ' . WP_CONTENT_DIR . '\cache\webaxones\assets\js\index.asset.php » doesn’t exist.' );
		}

    	$script_asset = require $script_asset_path;

		wp_enqueue_script(
			'webaxones-core',
			content_url() . '/cache/webaxones/assets/js/index.js',
			$script_asset['dependencies'],
			$script_asset['version']
		);
	}
}
