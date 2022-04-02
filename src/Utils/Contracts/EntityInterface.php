<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface EntityInterface
{
	/**
	 * Get entity input settings
	 *
	 * @return array
	 */
	public function getSettings(): array;

	/**
	 * Get entity slug
	 *
	 * @return string
	 */
	public function getSlug(): string;

	/**
	 * Process entity slug
	 *
	 * @return string
	 */
	public function sanitizeSlug(): string;
}
