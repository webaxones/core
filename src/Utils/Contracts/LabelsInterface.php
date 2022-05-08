<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface LabelsInterface
{
	/**
	 * Get global word
	 *
	 * @param  string $word
	 *
	 * @return string
	 */
	public function getGlobalWord( string $word ): string;

	/**
	 * Process classification labels by recomposing them with GlobalWords then adding results to $labels
	 *
	 * @param  array $labels
	 *
	 * @return array
	 */
	public function processClassificationLabels( array $labels ): array;

	/**
	 * Process optional labels added in declarations by adding them to $labels
	 *
	 * @param  array $labels
	 *
	 * @return array
	 */
	public function processOptionalLabels( array $labels ): array;

	/**
	 * Process custom content internationalized labels
	 *
	 * @return array
	 */
	public function processLabels(): array;


	/**
	 * Get custom content single label
	 *
	 * @param  string $label
	 *
	 * @return string
	 */
	public function getLabel( string $label ): string;


	/**
	 * Get custom content input labels
	 *
	 * @return array
	 */
	public function getLabels(): array;


	/**
	 * Get custom content gender
	 *
	 * @return bool
	 */
	public function getGender(): bool;


	/**
	 * Get custom content type
	 *
	 * @return string
	 */
	public function getCustomContentType(): string;


	/**
	 * Process custom content update messages
	 *
	 * @param  array  $messages Existing content update messages.
	 *
	 * @return array $messages Amended post update messages with new CPT update messages.
	 */
	public function processMessagesLabels( $messages ): array;
}
