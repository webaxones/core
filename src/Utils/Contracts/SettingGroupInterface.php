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
	 * Get fields
	 *
	 * @return array
	 */
	public function getFields(): array;

	/**
	 * Get children fields
	 *
	 * @return array
	 */
	public function getAllChildren(): array;

	/**
	 * Sets arguments according to the type of setting
	 *
	 * @param  array $field
	 *
	 * @return array
	 */
	public function setArgsFromSettingType( array $field ): array;
}
