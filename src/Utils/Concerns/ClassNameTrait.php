<?php

namespace Webaxones\Core\Utils\Concerns;

defined( 'ABSPATH' ) || exit;

trait ClassNameTrait
{
	/**
	 * Get current class short name without namespace
	 *
	 * @return string
	 */
	public function getCurrentClassShortName(): string
	{
		$currentClass = new \ReflectionClass( get_called_class() );
		return is_object( $currentClass ) ? $currentClass->getShortName() : '';
	}
}
