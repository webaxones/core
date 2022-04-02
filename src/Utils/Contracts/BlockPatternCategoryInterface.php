<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface BlockPatternCategoryInterface
{
	/**
	 * Get action to execute on block pattern
	 *
	 * @return string
	 */
	public function getAction(): string;

	/**
	 * Check if block pattern category already exists
	 *
	 * @return bool
	 */
	public function blockPatternCategoryAlreadyExists(): bool;

	/**
	 * Add new block pattern category
	 *
	 * @return void
	 */
	public function addBlockPatternCategory(): void;

	/**
	 * Remove block pattern category
	 *
	 * @return void
	 */
	public function removeBlockPatternCategory(): void;
}
