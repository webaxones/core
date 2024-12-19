<?php

namespace Webaxones\Core;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Hook\Hook;
use Webaxones\Core\I18n\I18n;


/**
 * Library engine
 */
class Library
{
	/**
	 * Initialize i18n
	 *
	 * @param  string $textDomain
	 *
	 * @return void
	 */
	public static function init( string $textDomain ): void
	{
		if ( ! defined( 'WEBAXONES_VENDOR_PATH' ) ) {
			$vendorPath = substr( __FILE__, 0, strpos( __FILE__, 'vendor\\' ) ) . 'vendor\\';
			define( 'WEBAXONES_VENDOR_PATH', $vendorPath );
		}

		$hook = new Hook();
		$i18n = new I18n( $textDomain );
		$hook->register( $i18n );
	}
}
