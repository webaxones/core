<?php

namespace Webaxones\Core\Classification;

defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Label\Labels;
use \DecaLog\Engine as Decalog;

/**
 * Custom taxonomy declaration
 */
class Taxonomy extends AbstractClassification
{
	/**
	 * Custom Taxonomy Object Type
	 *
	 * @var array
	 */
	protected array $objectType;

	public function __construct( array $parameters, Labels $labels )
	{
		parent::__construct( $parameters, $labels );

		$this->objectType = $parameters['settings']['object_type'] ?? [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerClassification(): void
	{
		register_taxonomy( $this->slug, $this->objectType, $this->args );
		Decalog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->slug . ' » Custom Taxonomy registered.' );
	}
}
