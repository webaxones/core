<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface SettingGroupInterface
{
	/**
	 * Get page slug
	 *
	 * @return string
	 */
	public function getPageSlug(): string;

	/**
	 * Register Setting
	 *
	 * @return void
	 */
	public function registerSetting(): void;

	/**
	 * Sets arguments according to the type of setting
	 *
	 * @param  array $field
	 *
	 * @return array
	 */
	public function setArgsFromSettingType( array $field ): array;
}
