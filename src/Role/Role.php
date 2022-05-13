<?php

namespace Webaxones\Core\Role;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\RoleInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Label\Labels;
use \Decalog\Engine as Decalog;

/**
 * Custom Role declaration
 */
class Role implements EntityInterface, RoleInterface, HookInterface, ActionInterface
{
	use ClassNameTrait;

	/**
	 * Input settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Input labels
	 *
	 * @var object
	 */
	protected object $labels;

	/**
	 * Output args
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * Role slug
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Role to clone slug
	 *
	 * @var string
	 */
	protected string $roleToCloneSlug;

	/**
	 * Action to execute
	 *
	 * @var string
	 */
	protected string $action;

	/**
	 * Cloned Role capabilities to remove
	 *
	 * @var array
	 */
	protected array $capabilitiesToRemove;

	/**
	 * Abstract Role Class Constructor
	 *
	 * @param  array $parameters
	 *
	 * @throws Exception
	 */
	public function __construct( array $parameters, Labels $labels )
	{
		if ( ! array_key_exists( 'settings', $parameters ) || empty( $parameters['settings'] ) ) {
			throw new Exception( 'Settings missing in content ' . $this->getCurrentClassShortName() . ' declaration' );
		}

		$this->settings             = $parameters['settings'];
		$this->labels               = $labels;
		$this->args['labels']       = $this->labels->processLabels();
		$this->slug                 = $this->sanitizeSlug();
		$this->roleToCloneSlug      = $this->settings['role_to_clone_slug'];
		$this->action               = $this->settings['action'];
		$this->capabilitiesToRemove = $this->settings['capabilities_to_remove'] ?? [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'init';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [ $this->getHookName() => [ 'finalProcess', 10, 1 ] ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSettings(): array
	{
		return $this->settings;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSlug(): string
	{
		return $this->slug;
	}

	/**
	 * {@inheritdoc}
	 */
	public function sanitizeSlug(): string
	{
		$settings = $this->getSettings();
		return sanitize_title( $settings['slug'] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRoleToCloneSlug(): string
	{
		return $this->roleToCloneSlug;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCapabilitiesToRemove(): array
	{
		return $this->capabilitiesToRemove;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * {@inheritdoc}
	 */
	public function roleAlreadyExists( string $role ): bool
	{
		if ( ! empty( $role ) ) {
			return $GLOBALS['wp_roles']->is_role( $role );
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isPredefinedRole(): bool
	{
		$predefinedRoles = [
			'administrator',
			'editor',
			'author',
			'contributor',
			'subscriber',
		];

		return in_array( $this->getSlug(), $predefinedRoles, true );
	}

	/**
	 * {@inheritdoc}
	 */
	public function addRole(): void
	{
		global $wp_roles;
		$wpRoles = $wp_roles;

		if ( ! isset( $wp_roles ) && class_exists( 'WP_Roles' ) ) {
			$wpRoles = new \WP_Roles();
		}

		// Get the role to clone
		$roleToClone = $wpRoles->get_role( $this->getRoleToCloneSlug() );

		// Cloning it
		$wpRoles->add_role( $this->getSlug(), $this->labels->getLabel( 'role_name' ), $roleToClone->capabilities );

		// Removing capabilities
		$new_role = get_role( $this->getSlug() );
		foreach ( $this->getCapabilitiesToRemove() as $cap ) {
			$new_role->remove_cap( $cap );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeRole(): void
	{
		remove_role( $this->getSlug() );
	}

	/**
	 * Final process callback function
	 *
	 * @return array
	 */
	public function finalProcess(): void
	{
		if ( 'add' === $this->getAction() && ! $this->roleAlreadyExists( $this->getSlug() ) ) {
			$this->addRole();
			DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' » Custom Role added.' );
		}

		if ( 'remove' === $this->getAction() && $this->roleAlreadyExists( $this->getSlug() ) && ! $this->isPredefinedRole() ) {
			$this->removeRole();
			DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' » Custom Role removed.' );
		}

		if ( 'update' === $this->getAction() && $this->roleAlreadyExists( $this->getSlug() ) ) {
			$this->removeRole();
			if ( ! $this->roleAlreadyExists( $this->getSlug() ) ) {
				$this->addRole();
				DecaLog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' » Custom Role updated.' );
			}
		}
	}
}
