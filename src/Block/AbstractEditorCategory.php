<?php

namespace Webaxones\Core\Block;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HooksInterface;

use Webaxones\Core\Label\Labels;

/**
 * Editor category declaration
 */
abstract class AbstractEditorCategory implements EntityInterface, HooksInterface
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
	 * Slug
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Output args
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * Action to execute
	 *
	 * @var string
	 */
	protected string $action;

	/**
	 * Abstract Class Constructor
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

		$this->settings = $parameters['settings'];
		$this->labels   = $labels;
		$this->slug     = $this->sanitizeSlug();
		$this->args     = $this->labels->processLabels();
		$this->action   = $this->settings['action'] ?? '';
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
	public function hook(): void
	{
		add_action( $this->getHookName(), [ $this, 'finalProcess' ] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'init';
	}
}
