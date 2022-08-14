<?php

namespace Webaxones\Core\Editor\Styles;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Webaxones\Core\Label\Labels;

/**
 * Editor styles declaration
 */
abstract class AbstractEditorStyle implements EntityInterface, HookInterface, ActionInterface
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
	 * Style name
	 *
	 * @var string
	 */
	protected string $styleName;

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

		$this->settings  = $parameters['settings'];
		$this->slug      = $this->sanitizeSlug();
		$this->labels    = $labels;
		$this->action    = $this->settings['action'] ?? '';
		$this->styleName = $this->settings['name'] ?? '';
		$this->setArgs();
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
		return [ $this->getHookName() => [ 'processStyle', 999, 1 ] ];
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
		return $settings['slug'];
	}

	/**
	 * Get Block style name
	 *
	 * @return string
	 */
	public function getStyleName(): string
	{
		$settings = $this->getSettings();
		return $settings['name'] ?? '';
	}

	/**
	 * Set Arguments dedicated to process final function
	 *
	 * @return void
	 */
	public function setArgs(): void
	{
		$this->args = [];
	}

	/**
	 * Get Arguments dedicated to process final function
	 *
	 * @return array
	 */
	public function getArgs(): array
	{
		return $this->args;
	}
}
