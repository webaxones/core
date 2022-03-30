<?php

namespace Webaxones\Core\Utils\Contracts;

defined( 'ABSPATH' ) || exit;

interface RoleInterface
{
	/**
	 * Get slug of role to clone to create a new one
	 *
	 * @return string
	 */
	public function getRoleToCloneSlug(): string;

	/**
	 * Get list of capabilities to remove from a role
	 *
	 * @return array
	 */
	public function getCapabilitiesToRemove(): array;

	/**
	 * Check if role already exists
	 *
	 * @param  string $role
	 *
	 * @return bool
	 */
	public function roleAlreadyExists( string $role ): bool;
}
