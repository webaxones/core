<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface SettingsGroupInterface
{
	/**
	 * Get array of settings group fields
	 *
	 * @return array
	 */
	public function getFields(): array;

	/**
	 * Register Settings group
	 *
	 * @return void
	 */
	public function registerSettings(): void;
}
