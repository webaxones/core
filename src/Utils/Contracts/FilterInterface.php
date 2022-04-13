<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface FilterInterface
{
	/**
	 * Get filters to register
	 *
	 * @return array
	 */
	public function getFilters(): array;
}
