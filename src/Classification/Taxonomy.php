<?php

namespace Webaxones\Core\Classification;

use Webaxones\Core\Label\Labels;

defined( 'ABSPATH' ) || exit;

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

	public function __construct( array $parameters = [], Labels $labels )
	{
		parent::__construct( $parameters, $labels );

		$this->objectType = $parameters['settings']['object_type'] ?? [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function executeCustomDeclarations(): void
	{
		register_taxonomy( $this->slug, $this->objectType, $this->args );
	}
}
