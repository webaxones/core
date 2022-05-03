<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface SettingInterface
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
	 * Send Setting to JS
	 *
	 * @return void
	 */
	public function sendSettingToJS(): void;

	/**
	 * Prepare setting to send to JS by merging labels and settings
	 *
	 * @return array
	 */
	public function prepareSetting(): array;

	/**
	 * Stringify Setting
	 *
	 * @param  array  $setting
	 *
	 * @return string
	 */
	public function stringifySetting( array $setting ): string;
}
