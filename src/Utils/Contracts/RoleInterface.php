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
	 * Get action to execute on role
	 *
	 * @return string
	 */
	public function getAction(): string;

	/**
	 * Check if role already exists
	 *
	 * @param  string $role
	 *
	 * @return bool
	 */
	public function roleAlreadyExists( string $role ): bool;

	/**
	 * Check is role to treat is a predefined role
	 *
	 * @return bool
	 */
	public function isPredefinedRole(): bool;

	/**
	 * Add new user role
	 *
	 * @return void
	 */
	public function addRole(): void;

	/**
	 * Remove user role
	 *
	 * @return void
	 */
	public function removeRole(): void;
}
