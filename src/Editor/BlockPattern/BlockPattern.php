<?php

namespace Webaxones\Core\Editor\BlockPattern;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Label\Labels;


/**
 * Handles creating and deleting a custom block pattern
 */
class BlockPattern implements EntityInterface, HookInterface, ActionInterface
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
	 * Action to execute
	 *
	 * @var string
	 */
	protected string $action;

	/**
	 * Block Pattern Class Constructor
	 *
	 * @param  array                        $parameters
	 * @param  \Webaxones\Core\Label\Labels $labels
	 * @throws Exception
	 */
	public function __construct( array $parameters, Labels $labels )
	{
		if ( ! array_key_exists( 'settings', $parameters ) || empty( $parameters['settings'] ) ) {
			throw new Exception( 'Settings missing in content ' . $this->getCurrentClassShortName() . ' declaration' );
		}

		$this->labels   = $labels;
		$this->settings = array_merge(
			$parameters['settings'],
			$this->labels->processLabels(),
		);
		$this->slug     = $this->sanitizeSlug();
		$this->action   = $this->settings['action'];
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
	 * {@inheritdoc}
	 */
	public function getAction(): string
	{
		return $this->action;
	}

	/**
	 * Check if block pattern already exists or not
	 *
	 * @return bool Does it exists?
	 */
	public function patternAlreadyExists(): bool
	{
		return \WP_Block_Patterns_Registry::get_instance()->is_registered( $this->getSlug() );
	}

	/**
	 * Register custom block pattern
	 *
	 * @return void
	 */
	public function addPattern(): void
	{
		if ( ! $this->patternAlreadyExists( $this->getSlug() ) ) {
			register_block_pattern( $this->getSlug(), $this->getSettings() );
		}
	}

	/**
	 * Unregister custom block pattern
	 *
	 * @return void
	 */
	public function removePattern(): void
	{
		if ( $this->patternAlreadyExists( $this->getSlug() ) ) {
			unregister_block_pattern( $this->getSlug() );
		}
	}

	/**
	 * Final process callback function: add pattern || remove pattern
	 *
	 * @return void
	 */
	public function finalProcess(): void
	{
		if ( 'add' === $this->getAction() ) {
			$this->addPattern();
		}

		if ( 'remove' === $this->getAction() ) {
			$this->removePattern();
		}
	}
}
