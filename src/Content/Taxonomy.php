<?php

namespace Webaxones\Core\Content;

defined( 'ABSPATH' ) || exit;

/**
 * Custom taxonomy declaration
 */
class Taxonomy extends AbstractContent
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
	public function registerCustomDeclarations(): void
	{
		register_taxonomy( $this->slug, $this->objectType, $this->args );
	}
}
