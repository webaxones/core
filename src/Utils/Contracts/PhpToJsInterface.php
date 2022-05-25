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
	 * Prepare Data to send to JS by merging labels and settings then simplify the arary
	 *
	 * @return array
	 */
	public function prepareData(): array;

	/**
	 * Populate labels in fields subarray
	 *
	 * @param  array $data
	 *
	 * @return array
	 */
	public function prepareLabels( array $data ): array;

	/**
	 * Store all data fields subarray
	 *
	 * @param  array $data
	 *
	 * @return array
	 */
	public function prepareGroups( array $data ): array;

	/**
	 * Stringify Data
	 *
	 * @param  array  $data
	 *
	 * @return string
	 */
	public function stringifyData( array $data ): string;
}
