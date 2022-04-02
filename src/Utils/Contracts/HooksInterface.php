<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface HooksInterface
{
	/**
	 * Adds callback function to hook
	 *
	 * @return void
	 */
	public function hook(): void;

	/**
	 * Get hook name depending on current entity
	 *
	 * @return string
	 */
	public function getHookName(): string;
}
