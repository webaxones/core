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
	 * Target object
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
		return [ $this->getHookName() => [ 'finalProcess', 10 ] ];
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
			return \get_user_by( 'ID', intval( $this->settings['target'] ) );
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

		$capabilities = $this->target->allcaps;
		if ( isset( $capabilities[ $capability ] ) ) {
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
		if ( $this->capabilityAlreadyExistsOnTarget( $this->getSlug() ) ) {
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' » Custom Capability already exists on target.' );
		} else {
			$this->target->add_cap( $this->getSlug(), true );
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' » Custom Capability added.' );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeCapability(): void
	{
		if ( ! $this->capabilityAlreadyExistsOnTarget( $this->getSlug() ) ) {
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' » Custom Capability doesn’t exists on target.' );
		} else {
			$this->target->remove_cap( $this->getSlug() );
			Decalog::eventsLogger( 'webaxones-core' )->info( '« ' . $this->getSlug() . ' » Custom Capability removed.' );
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
