<?php

namespace Webaxones\Core\Role;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use \DecaLog\Engine as Decalog;

/**
 * Custom Capability declaration
 */
class Capability implements EntityInterface, HookInterface, ActionInterface
{
	use ClassNameTrait;

	/**
	 * Input settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Output args
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * Capability slug
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Role object
	 *
	 * @var object
	 */
	protected object $role;

	/**
	 * Action to execute
	 *
	 * @var string
	 */
	protected string $action;

	/**
	 * Capability Class Constructor
	 *
	 * @param  array $parameters
	 *
	 * @throws Exception
	 */
	public function __construct( array $parameters )
	{
		if ( ! array_key_exists( 'settings', $parameters ) || empty( $parameters['settings'] ) ) {
			throw new Exception( 'Settings missing in content ' . $this->getCurrentClassShortName() . ' declaration' );
		}

		$this->settings = $parameters['settings'];
		$this->slug     = $this->sanitizeSlug();
		if ( ! array_key_exists( 'role_to_add_cap_to', $this->settings ) || empty( $this->settings['role_to_add_cap_to'] ) ) {
			throw new Exception( 'Role to add capability to is missing in content: ' . $this->getCurrentClassShortName() . ' declaration' );
		}
		$this->role   = $this->setRole( $this->settings['role_to_add_cap_to'] );
		$this->action = $this->settings['action'];
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
		return [ $this->getHookName() => [ 'finalProcess', 11, 1 ] ];
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
	 * Set the role to add a capability to
	 *
	 * @param  void   $roleSlug
	 *
	 * @return object
	 */
	public function setRole( $roleSlug ): object
	{
		return get_role( $roleSlug );
	}

	/**
	 * Get the role to add a capability to
	 *
	 * @return object
	 */
	public function getRole(): object
	{
		return $this->role;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * Check if capability already exists on role
	 *
	 * @param  string $capability
	 *
	 * @return bool
	 */
	public function capabilityAlreadyExistsOnRole( string $capability ): bool
	{
		if ( empty( $capability ) ) {
			return false;
		}
		$role         = $this->getRole();
		$capabilities = $role->capabilities;
		return in_array( $capability, (array) $capabilities, true );
	}

	/**
	 * Add capability to role
	 *
	 * @return void
	 */
	public function addCapability(): void
	{
		$role = $this->getRole();
		$role->add_cap( $this->getSlug(), true );
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeCapability(): void
	{
		$role = $this->getRole();
		$role->remove_cap( $this->getSlug(), true );
	}

	/**
	 * Final process callback function
	 *
	 * @return array
	 */
	public function finalProcess(): void
	{
		if ( 'add' === $this->getAction() && ! $this->capabilityAlreadyExistsOnRole( $this->getSlug() ) ) {
			$this->addCapability();
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' » Custom Capability added.' );
		}

		if ( 'remove' === $this->getAction() && $this->capabilityAlreadyExistsOnRole( $this->getSlug() ) ) {
			$this->removeCapability();
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' » Custom Capability removed.' );
		}
	}
}
