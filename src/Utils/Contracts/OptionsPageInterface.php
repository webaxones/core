<?php

namespace Webaxones\Core\Contracts;

defined( 'ABSPATH' ) || exit;

interface OptionsPageInterface
{
	/**
	 * Get location of options page
	 *
	 * @return string
	 */
	public function getLocation(): string;

	/**
	 * Get locations menu slugs
	 *
	 * @return array
	 */
	public function getSlugLocations(): array;

	/**
 	 * Function to add the options page: determines the location
	 *
	 * @return string
	 */
	public function getAddPageFunction(): string;
}
