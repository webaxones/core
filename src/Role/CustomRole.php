<?php

namespace Webaxones\Core\Role;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\RoleInterface;
use Webaxones\Core\Utils\Contracts\HooksInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Label\Labels;

/**
 * Custom Role declaration
 */
class CustomRole implements EntityInterface, RoleInterface, HooksInterface
{
	use ClassNameTrait;

	/**
	 * Custom Role input settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Custom Role input labels
	 *
	 * @var object
	 */
	protected object $labels;

	/**
	 * Custom Role output args
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * Custom Role slug
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Custom Role to clone slug
	 *
	 * @var string
	 */
	protected string $roleToCloneSlug;

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
		$this->slug                 = $this->processSlug();
		$this->roleToCloneSlug      = $this->settings['role_to_clone_slug'];
		$this->capabilitiesToRemove = $this->settings['capabilities_to_remove'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function hook(): void
	{
		add_action( $this->getHookName(), [ $this, 'registerCustomDeclarations' ] );
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
	public function processSlug(): string
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
	public function registerCustomDeclarations(): void
	{
		if ( $this->roleAlreadyExists( $this->getSlug() ) ) {
			return;
		}

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

}
