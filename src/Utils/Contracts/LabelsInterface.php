<?php

namespace WaxCustom\Contracts;

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
