<?php

namespace Webaxones\Core\Classification;

defined( 'ABSPATH' ) || exit;

use Exception;

use Webaxones\Core\Utils\Contracts\EntityInterface;
use Webaxones\Core\Utils\Contracts\ClassificationInterface;
use Webaxones\Core\Utils\Contracts\HooksInterface;

use Webaxones\Core\Utils\Concerns\OptionalSettingsTrait;
use Webaxones\Core\Utils\Concerns\ClassNameTrait;

use Webaxones\Core\Label\Labels;

/**
 * Classification declaration
 */
abstract class AbstractClassification implements EntityInterface, ClassificationInterface, HooksInterface
{
	use OptionalSettingsTrait;
	use ClassNameTrait;

	/**
	 * Classification input settings
	 *
	 * @var array
	 */
	protected array $settings;

	/**
	 * Classification input labels
	 *
	 * @var object
	 */
	protected object $labels;

	/**
	 * Classification output args
	 *
	 * @var array
	 */
	protected array $args;

	/**
	 * Classification slug
	 *
	 * @var string
	 */
	protected string $slug;

	/**
	 * Abstract Classification Class Constructor
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

		$this->settings        = $parameters['settings'];
		$this->labels          = $labels;
		$this->args['labels']  = $this->labels->processLabels();
		$this->slug            = $this->processSlug();
		$this->args['rewrite'] = $this->processRewrite();
		$this->args            = array_merge( $this->args, $this->processVisibilities() );
		$this->args            = array_merge( $this->args, $this->processCapabilities() );
		$this->args            = array_merge( $this->args, $this->processAccessibilities() );
		$this->args            = array_merge( $this->args, $this->processCapacities() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function hook(): void
	{
		add_action( $this->getHookName(), [ $this, 'finalProcess' ] );

		add_filter( $this->getMessagesHookName(), [ $this->labels, 'processMessagesLabels' ] );
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
	public function getMessagesHookName(): string
	{
		if ( 'PostType' === $this->getCurrentClassShortName() ) {
			return 'post_updated_messages';
		}

		if ( 'Taxonomy' === $this->getCurrentClassShortName() ) {
			return 'term_updated_messages';
		}

		return '';
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
	public function processVisibilities(): array
	{
		$settings = $this->getSettings();

		if ( array_key_exists( 'public', $settings ) && false === $settings['public'] ) {
			return false;
		}

		$visibilities = [
			'public' => $settings['public'],
		];

		$options = [
			'menu_icon',
			'menu_position',
			'show_ui',
			'show_in_menu',
			'show_in_admin_bar',
			'show_in_nav_menus',
			'show_in_quick_edit',
			'show_admin_column',
			'exclude_from_search',
		];

		$visibilities = $this->AddPassedOptions( $options, $visibilities, $this->getSettings() );

		return $visibilities;
	}

	/**
	 * {@inheritdoc}
	 */
	public function processAccessibilities(): array
	{
		$accessibilities = [];

		$options = [
			'query_var',
			'publicly_queryable',
			'show_in_rest',
			'rest_base',
			'rest_namespace',
			'rest_controller_class',
		];

		$accessibilities = $this->AddPassedOptions( $options, $accessibilities, $this->getSettings() );

		return $accessibilities;
	}

	/**
	 * {@inheritdoc}
	 */
	public function processRewrite(): array
	{
		$settings = $this->getSettings();

		if ( array_key_exists( 'rewrite', $settings ) ) {
			return $settings['rewrite'];
		}

		$rewrite = [
			'slug' => $this->getSlug(),
		];

		$options = [
			'with_front',
			'feeds',
			'pages',
			'ep_mask',
		];

		$rewrite = $this->AddPassedOptions( $options, $rewrite, $this->getSettings() );

		return $rewrite;
	}

	/**
	 * {@inheritdoc}
	 */
	public function processCapabilities(): array
	{
		$capabilities = [];

		$options = [
			'capability_type',
			'capabilities',
			'map_meta_cap',
			'register_meta_box_cb',
		];

		$capabilities = $this->AddPassedOptions( $options, $capabilities, $this->getSettings() );

		return $capabilities;
	}

	/**
	 * {@inheritdoc}
	 */
	public function processCapacities(): array
	{
		$capacities = [];

		$options = [
			'hierarchical',
			'taxonomies',
			'supports',
			'has_archive',
			'can_export',
			'delete_with_user',
			'template',
			'template_lock',
		];

		$capacities = $this->AddPassedOptions( $options, $capacities, $this->getSettings() );

		return $capacities;
	}
}
