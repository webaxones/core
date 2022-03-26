<?php

namespace Webaxones\Core\I18n;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin's internationalization
 */
class I18n
{
	/**
	 * Load text-domain
	 *
	 * @return void
	 */
	public function loadPluginTextdomain()
	{
		load_plugin_textdomain( 'wax-custom-content', false, plugin_dir_path( __FILE__ ) . 'languages' );
	}
}
