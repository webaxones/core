<?php

namespace Webaxones\Core\Contracts;

defined( 'ABSPATH' ) || exit;

interface ContentInterface
{
	/**
	 * Get content input settings
	 *
	 * @return array
	 */
	public function getSettings(): array;

	/**
	 * Get content slug
	 *
	 * @return string
	 */
	public function getSlug(): string;

	/**
	 * Process content slug
	 *
	 * @return string
	 */
	public function processSlug(): string;

	/**
	 * Process content visibilities
	 *
	 * @return array
	 */
	public function processVisibilities(): array;

	/**
	 * Process content data accessibilities
	 *
	 * @return array
	 */
	public function processAccessibilities(): array;

	/**
	 * Process content capabilities types
	 *
	 * @return array
	 */
	public function processCapabilities(): array;

	/**
	 * Process content capacities
	 *
	 * @return array
	 */
	public function processCapacities(): array;

	/**
	 * Process content rewrite rules
	 *
	 * @return array
	 */
	public function processRewrite(): array;
}
