<?php
namespace Webaxones\Core\Admin;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\PhpToJsInterface;

/**
 * Manage AdminScript
 */
class AdminScript extends AbstractAsset implements PhpToJsInterface
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
		if( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		$this->checkAndPrepareAssets();

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
		return 'let webaxonesApps = ' . wp_json_encode( $data );
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
