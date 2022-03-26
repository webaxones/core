<?php

namespace Webaxones\Core\Contracts;

defined( 'ABSPATH' ) || exit;

interface HooksInterface
{
	/**
	 * Adds callback functions to hooks
	 *
	 * @return void
	 */
	public function hook(): void;

	/**
	 * Register custom declarations
	 *
	 * @return array
	 */
	public function registerCustomDeclarations(): void;

	/**
	 * Get hook name depending on current location and current process
	 *
	 * @return string
	 */
	public function getHookName(): string;
}
