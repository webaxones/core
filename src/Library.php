<?php

namespace Webaxones\Core;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Hook\Hook;
use Webaxones\Core\I18n\I18n;
use \Decalog\Engine as Decalog;

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
		DecaLog::initLibrary( 'webaxones-entities', 'Webaxones Entities Library', '1.0.0' );

		$hook = new Hook();
		$i18n = new I18n( $textDomain );
		$hook->register( $i18n );
	}
}