<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface ClassificationInterface
{
	/**
	 * Process classification visibilities
	 *
	 * @return array
	 */
	public function processVisibilities(): array;

	/**
	 * Process classification data accessibilities
	 *
	 * @return array
	 */
	public function processAccessibilities(): array;

	/**
	 * Process classification capabilities types
	 *
	 * @return array
	 */
	public function processCapabilities(): array;

	/**
	 * Process classification capacities
	 *
	 * @return array
	 */
	public function processCapacities(): array;

	/**
	 * Process classification rewrite rules
	 *
	 * @return array
	 */
	public function processRewrite(): array;

	/**
	 * Set classification declaration arguments
	 *
	 * @return void
	 */
	public function setArgs(): void;
}
