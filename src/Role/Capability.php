<?php

namespace Webaxones\Core\Role;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;



/**
 * Handles creating, deleting custom capability
 *
 * Capability can be added to a Role or a User
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
	 * Target object: WP_Role or WP_User
	 *
	 * @var object|null
	 */
	protected ?object $target;

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
		if ( ! array_key_exists( 'target', $this->settings ) || empty( $this->settings['target'] ) ) {
			throw new Exception( 'Target to add capability to is missing in: ' . $this->getCurrentClassShortName() . ' declaration' );
		}
		if ( ! array_key_exists( 'target_type', $this->settings ) || empty( $this->settings['target_type'] ) ) {
			throw new Exception( 'target_type is missing in: ' . $this->getCurrentClassShortName() . ' declaration' );
		}
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
		return [ $this->getHookName() => [ 'finalProcess' ] ];
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
	 * Get the target to add a capability to
	 *
	 * @return object|null
	 */
	public function getTarget(): object|null
	{
		if ( 'role' === $this->settings['target_type'] ) {
			return get_role( $this->settings['target'] );
		}
		if ( 'user' === $this->settings['target_type'] ) {
			return get_user_by( 'ID', intval( $this->settings['target'] ) );
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * Check if capability already exists on target
	 *
	 * @param  string $capability
	 *
	 * @return bool
	 */
	public function capabilityAlreadyExistsOnTarget( string $capability ): bool
	{
		if ( empty( $capability ) ) {
			return false;
		}

		if ( $this->target->has_cap( $capability ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Add capability to target
	 *
	 * @return void
	 */
	public function addCapability(): void
	{
		if ( ! $this->capabilityAlreadyExistsOnTarget( $this->getSlug() ) ) {
			$this->target->add_cap( $this->getSlug(), true );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeCapability(): void
	{
		if ( $this->capabilityAlreadyExistsOnTarget( $this->getSlug() ) ) {
			$this->target->remove_cap( $this->getSlug() );
		}
	}

	/**
	 * Final process callback function
	 *
	 * @return array
	 */
	public function finalProcess(): void
	{
		$this->target = $this->getTarget();

		if ( 'add' === $this->getAction() ) {
			$this->addCapability();
		}

		if ( 'remove' === $this->getAction() ) {
			$this->removeCapability();
		}
	}
}
