<?php

namespace Webaxones\Core;

use Exception;

use Webaxones\Core\Contracts\OptionsPageInterface;
use Webaxones\Core\Contracts\HooksInterface;

use Webaxones\Core\Concerns\ClassNameTrait;

/**
 * Custom options pages declaration
 */
abstract class AbstractOptionsPage implements OptionsPageInterface, HooksInterface
{
	use ClassNameTrait;

	/**
	 * Option page input settings
	 *
	 * @var array
	 */
	protected array $parameters;

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
	 * Locations menu slugs
	 *
	 * @var array
	 */
	protected array $slugLocations;


	/**
	 * Abstract Custom options page Class Constructor
	 *
	 * @param  array $parameters
	 *
	 * @throws Exception
	 */
	public function __construct( array $parameters = [], Labels $labels )
	{
		if ( ! array_key_exists( 'settings', $parameters ) || empty( $parameters['settings'] ) ) {
			throw new Exception( 'Settings missing in custom content ' . $this->getCurrentClassShortName() . ' declaration' );
		}

		$this->settings      = $parameters['settings'];
		$this->labels        = $labels;
		$this->settings      = array_merge( $this->settings, $this->labels->processLabels() );
		$this->location      = $parameters['settings']['location'];
		$this->slugLocations = [
			'dashboard'  => 'index.php',
			'posts'      => 'edit.php',
			'pages'      => 'edit.php?post_type=page',
			'comments'   => 'edit-comments.php',
			'theme'      => 'themes.php',
			'plugins'    => 'plugins.php',
			'users'      => 'users.php',
			'management' => 'tools.php',
			'options'    => 'options-general.php',
		];
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
	public function getLocation(): string
	{
		return $this->location;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSlugLocations(): array
	{
		return $this->slugLocations;
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
		if ( in_array( $this->getLocation(), $this->getSlugLocations(), true ) ) {
			return 'add_' . $this->getLocation() . '_page';
		}
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		if ( 'OptionsPage' === $this->getCurrentClassShortName() ) {
			return 'admin_menu';
		}

		if ( 'AcfOptionsPage' === $this->getCurrentClassShortName() ) {
			return 'acf/init';
		}

		return '';
	}
}
