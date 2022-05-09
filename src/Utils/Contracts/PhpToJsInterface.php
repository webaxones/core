<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface PhpToJsInterface
{
	/**
	 * Get hook name for inline script
	 *
	 * @return string
	 */
	public function getInlineScriptHookName(): string;

	/**
	 * Send Data to JS
	 *
	 * @return void
	 */
	public function sendDataToJS(): void;

	/**
	 * Prepare Data to send to JS by merging labels and settings
	 *
	 * @return array
	 */
	public function prepareData(): array;

	/**
	 * Format Data by populating labels inside fields and removing labels in global array
	 *
	 * @return array
	 */
	public function formatData(): array;

	/**
	 * Stringify Data
	 *
	 * @param  array  $data
	 *
	 * @return string
	 */
	public function stringifyData( array $data ): string;
}
