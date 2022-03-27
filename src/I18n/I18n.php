<?php

namespace Webaxones\Core\I18n;

defined( 'ABSPATH' ) || exit;

/**
 * Labels internationalization
 */
class I18n
{
	/**
	 * Load text-domain
	 *
	 * @return void
	 */
	public function loadPluginTextdomain( $textDomain )
	{
		load_plugin_textdomain( $textDomain, false, plugin_dir_path( __FILE__ ) . 'languages' );
	}
}
