<?php

namespace Webaxones\Core\Option;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\OptionsPageInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;
use Webaxones\Core\Utils\Contracts\ActionInterface;

use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Config\AdminMenuSlugs;
use Webaxones\Core\Label\Labels;

/**
 * Custom options pages declaration
 */
abstract class AbstractOptionsPage implements EntityInterface, OptionsPageInterface, HookInterface, ActionInterface
{
	use ClassNameTrait;

	/**
	 * Option page slug
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Option page input settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Option page input labels
	 *
	 * @var object
	 */
	protected object $labels;

	/**
	 * Location of options page
	 *
	 * @var string
	 */
	protected string $location;

	/**
	 * Admin menu slugs
	 *
	 * @var array
	 */
	protected array $adminMenuSlugs;


	/**
	 * Abstract Custom options page Class Constructor
	 *
	 * @param  array $parameters
	 *
	 * @throws Exception
	 */
	public function __construct( array $parameters, Labels $labels )
	{
		if ( ! array_key_exists( 'settings', $parameters ) || empty( $parameters['settings'] ) ) {
			throw new Exception( 'Settings missing in custom content ' . $this->getCurrentClassShortName() . ' declaration' );
		}

		$this->settings       = $parameters['settings'];
		$this->slug           = $this->sanitizeSlug();
		$this->labels         = $labels;
		$this->settings       = array_merge( $this->settings, $this->labels->processLabels() );
		$this->location       = $parameters['settings']['location'];
		$this->adminMenuSlugs = AdminMenuSlugs::$adminMenuSlugs;
	}

	/**
	 * {@inheritdoc}
	 */
	abstract public function getHookName(): string;

	/**
	 * {@inheritdoc}
	 */
	public function getActions(): array
	{
		return [ $this->getHookName() => [ 'addOptionsPage', 10, 1 ] ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLocation(): string
	{
		return $this->location;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAdminMenuSlugs(): array
	{
		return $this->adminMenuSlugs;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAddPageFunction(): string
	{
		if ( 'top_level' === $this->getLocation() ) {
			return 'add_menu_page';
		}
		if ( 'custom' === $this->getLocation() ) {
			return 'add_menu_page';
		}
		if ( in_array( $this->getLocation(), $this->getAdminMenuSlugs(), true ) ) {
			return 'add_' . $this->getLocation() . '_page';
		}
		return '';
	}

	/**
	 * Add options page
	 *
	 * @return void
	 */
	abstract public function addOptionsPage(): void;

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
}
