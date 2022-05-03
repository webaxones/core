<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface PhpToJsInterface
{
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
	 * Stringify Data
	 *
	 * @param  array  $data
	 *
	 * @return string
	 */
	public function stringifyData( array $data ): string;
}
