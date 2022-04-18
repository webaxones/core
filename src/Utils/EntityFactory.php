<?php

namespace Webaxones\Core\Utils;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Exception;
use Webaxones\Core\Config\GlobalWords;
use Webaxones\Core\Label\Labels;

/**
 * Entity factory
 */
class EntityFactory
{
	use ClassNameTrait;

	/**
	 * Entity name
	 *
	 * @var string
	 */
	protected string $className;

	/**
	 * Entity settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Entity labels
	 *
	 * @var array
	 */
	protected array $labels;

	/**
	 * Entity factory declaration
	 *
	 * @param  string $entity
	 * @param  array  $settings
	 */
	public function __construct( array $settings = [] )
	{
		\DecaLog\Engine::initLibrary( 'webaxones-entities', 'Webaxones Entities Library', '1.0.0' );
		$this->settings  = $settings;
		$this->className = $settings['entity'];
		$this->labels    = $settings['labels'] ?? [];
	}

	/**
	 * Create entity
	 *
	 * @return object
	 *
	 * @throws Exception
	 */
	public function createEntity(): object
	{
		$classShortName = substr( $this->className, strrpos( $this->className, '\\' ) + 1 );

		if ( ! class_exists( $this->className ) ) {
			throw new Exception( '« ' . $this->className . ' » doesn’t exist. Wrong content type name passed as parameter.' );
		}

		$globalWords = GlobalWords::getValues();
		$labels      = new Labels( $classShortName, $this->settings['settings']['slug'], $this->labels, $globalWords );
		return new $this->className( $this->settings, $labels );
	}
}
