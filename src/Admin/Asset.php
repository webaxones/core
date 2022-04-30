<?php
namespace Webaxones\Core\Admin;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Exception;

/**
 * Register asset script
 */
class Asset implements HookInterface, ActionInterface
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
		return [ $this->getHookName() => [ 'registerScripts', 10, 1 ] ];
	}

	public function registerScripts(): void
	{
		$script_asset_path = WP_CONTENT_DIR . '\wax-assets\js\index.asset.php';
		if ( ! file_exists( $script_asset_path ) ) {
			throw new Exception( '« ' . WP_CONTENT_DIR . '\wax-assets\js\index.asset.php » doesn’t exist.' );
		}

    	$script_asset = require( $script_asset_path );

		wp_enqueue_script(
			'webaxones-core',
			content_url() . '/wax-assets/js/index.js',
			$script_asset['dependencies'],
			$script_asset['version']
		);
	}
}
