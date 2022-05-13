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
	 * Prepare labels in Data by populating labels inside fields and removing labels in global array
	 *
	 * @param  array $data
	 *
	 * @return array
	 */
	public function prepareLabels( array $data ): array;

	/**
	 * Prepare labels in Data by populating labels inside fields and removing labels in global array
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
