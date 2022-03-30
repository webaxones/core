<?php

namespace Webaxones\Core\Utils\Contracts;

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
	 * Get admin menu slugs
	 *
	 * @return array
	 */
	public function getAdminMenuSlugs(): array;

	/**
 	 * Function to add the options page: determines the location
	 *
	 * @return string
	 */
	public function getAddPageFunction(): string;
}
