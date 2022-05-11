<?php
namespace Webaxones\Core\Admin;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\PhpToJsInterface;

/**
 * Manage Script
 */
class Script extends AbstractAsset implements PhpToJsInterface
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

		wp_enqueue_style('wp-edit-post');
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendDataToJS(): void
	{
		wp_add_inline_script( 'webaxones-core', $this->stringifyData( $this->formatData() ), 'before' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function stringifyData( array $data ): string
	{
		return 'let webaxonesApps = ' . wp_json_encode( $data );
	}

	/**
	 * {@inheritdoc}
	 */
	public function formatData(): array
	{
		return $this->prepareData();
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepareData(): array
	{
		return [];
	}
}
