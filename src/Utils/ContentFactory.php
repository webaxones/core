<?php

namespace Webaxones\Core\Utils;

defined( 'ABSPATH' ) || exit;

use Exception;
use Webaxones\Core\Label\GlobalWords;
use Webaxones\Core\Label\Labels;

/**
 * Content factory
 */
class ContentFactory
{
	/**
	 * Content name
	 *
	 * @var string
	 */
	protected string $className;

	/**
	 * Content settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Content factory declaration
	 *
	 * @param  string $type
	 * @param  array  $settings
	 */
	public function __construct( array $settings = [] )
	{
		$this->className = ( 'PostType' === $settings['type'] || 'Taxonomy' === $settings['type'] ) ? 'Webaxones\\Core\\Classification\\' . $settings['type'] : 'Webaxones\\Core\\Option\\' . $settings['type'];
		$this->settings  = $settings;
	}

	/**
	 * Create custom content
	 *
	 * @return object
	 *
	 * @throws Exception
	 */
	public function createCustomContent(): object
	{
		$classShortName = substr( $this->className, strpos( $this->className, '\\' ) + 1 );
		if ( ! class_exists( $this->className ) ) {
			throw new Exception( '« ' . $classShortName . ' » doesn’t exist. Wrong content type name passed as parameter.' );
		}

		$globalWords = GlobalWords::getValues();
		$labels      = new Labels( $classShortName, $this->settings['settings']['slug'], $this->settings['labels'], $globalWords );
		return new $this->className( $this->settings, $labels );
	}
}
