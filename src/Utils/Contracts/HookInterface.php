<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface HookInterface
{
	/**
	 * Get hook name depending on current entity
	 *
	 * @return string
	 */
	public function getHookName(): string;
}
