<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface SettingBlockInterface
{
	/**
	 * Register Setting
	 *
	 * @return void
	 */
	public function registerSetting(): void;
}
