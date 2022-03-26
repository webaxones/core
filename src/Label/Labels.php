<?php

namespace Webaxones\Core;

use Webaxones\Core\Contracts\LabelsInterface;

use Webaxones\Core\Concerns\OptionalSettingsTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Internationalized labels declaration
 */
class Labels implements LabelsInterface
{
	use OptionalSettingsTrait;

	/**
	 * Custom content global words
	 *
	 * @var array
	 */
	protected array $globalWords;

	/**
	 * Custom content labels
	 *
	 * @var array
	 */
	protected array $labels;

	/**
	 * Custom content type
	 *
	 * @var string
	 */
	protected string $customContentType;

	/**
	 * Custom content slug
	 *
	 * @var string
	 */
	protected string $contentSlug;

	/**
	 * Custom content gender
	 *
	 * @var bool
	 */
	protected bool $contentGender;

	/**
	 * Abstract Custom Content Class Constructor
	 *
	 * @param  array $parameters
	 * @param  string $classShortName
	 * @param  array $globalWords
	 */
	public function __construct( array $parameters, string $classShortName, array $globalWords )
	{
		$this->globalWords       = $globalWords;
		$this->labels            = $parameters['labels'];
		$this->contentSlug       = array_key_exists( 'slug', $parameters['settings'] ) ? $parameters['settings']['slug'] : '';
		$this->contentGender     = array_key_exists( 'gender', $parameters['settings'] ) && 'f' === $parameters['settings']['gender'] ? 1 : 0;
		$this->customContentType = $classShortName;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabels(): array
	{
		return $this->labels;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel( string $label ): string
	{
		$labels = $this->getLabels();
		return $labels[ $label ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCustomContentType(): string
	{
		return $this->customContentType;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getGender(): bool
	{
		return $this->contentGender;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getGlobalWord( string $word ): string
	{
		return ( is_array( $this->globalWords ) && array_key_exists( $word, $this->globalWords ) ) ? $this->globalWords[ $word ] : '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function processLabels(): array
	{
		$labels = [];
		if ( array_key_exists( 'singular_name', $this->getLabels() ) && array_key_exists( 'plural_name', $this->getLabels() ) ) {
			$labels = [
				'name'               => $this->getLabel( 'plural_name' ),
				'singular_name'      => $this->getLabel( 'singular_name' ),
				'edit_item'          => $this->getGlobalWord( 'edit' ) . ' ' . strtolower( $this->getLabel( 'the_singular' ) ),
				'update_item'        => $this->getGlobalWord( 'update' ) . ' ' . strtolower( $this->getLabel( 'the_singular' ) ),
				'view_item'          => $this->getGlobalWord( 'view' ) . ' ' . strtolower( $this->getLabel( 'the_singular' ) ),
				'view_items'         => $this->getGlobalWord( 'view' ) . ' ' . strtolower( $this->getLabel( 'the_plural' ) ),
				'add_new_item'       => $this->getGlobalWord( 'add' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'a_feminine' : 'a_masculine' ) . ' ' . strtolower( $this->getLabel( 'new_item' ) ),
				'archives'           => $this->getGlobalWord( 'list_of' ) . ' ' . strtolower( $this->getLabel( 'plural_name' ) ),
				'attributes'         => $this->getGlobalWord( 'attributes_of' ) . ' ' . strtolower( $this->getLabel( 'plural_name' ) ),
				'not_found'          => $this->getGlobalWord( 'not_found' ),
				'not_found_in_trash' => $this->getGlobalWord( 'not_found_in_trash' ),
				'search_items'       => $this->getGlobalWord( 'search_items' ),
				'menu_name'          => $this->getLabel( 'plural_name' ),
			];
		}

		$options = [
			'parent_item_colon',
			'insert_into_item',
			'uploaded_to_this_item',
			'featured_image',
			'set_featured_image',
			'remove_featured_image',
			'use_featured_image',
			'filter_items_list',
			'filter_by_date',
			'items_list_navigation',
			'items_list',
			'item_published',
			'item_published_privately',
			'item_reverted_to_draft',
			'item_scheduled',
			'item_updated',
			'item_link',
			'item_link_description',
			'popular_items',
			'parent_item',
			'name_field_description',
			'slug_field_description',
			'parent_field_description',
			'desc_field_description',
			'new_item_name',
			'separate_items_with_commas',
			'add_or_remove_items',
			'choose_from_most_used',
			'no_terms',
			'filter_by_item',
			'items_list_navigation',
			'items_list',
			'back_to_items',
			'item_link',
			'item_link_description',
			'page_title',
			'menu_title',
		];

		$labels = $this->AddPassedOptions( $options, $labels, $this->getLabels() );
		return $labels;
	}

	/**
	 * {@inheritdoc}
	 */
	public function processMessagesLabels( $messages ): array
	{
		$post = get_post();

		if ( 'PostType' === $this->getCustomContentType() && ! is_null( $post ) ) {
			$post_type        = get_post_type( $post );
			$post_type_object = get_post_type_object( $post_type );

			$messages[ $this->contentSlug ] = [
				0  => '', // Unused. Messages start at index 1.
				1  => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'updated_feminine' : 'updated_masculine' ) . '.',
				2  => $this->getGlobalWord( 'custom_field_updated' ),
				3  => $this->getGlobalWord( 'custom_field_updated' ),
				4  => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'updated_feminine' : 'updated_masculine' ) . '.',
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( $this->getGlobalWord( $this->getGender() ? 'restored_to_revision_feminine' : 'restored_to_revision_masculine' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'published_feminine' : 'published_masculine' ) . '.',
				7  => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'saved_feminine' : 'saved_masculine' ) . '.',
				8  => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'submitted_feminine' : 'submitted_masculine' ) . '.',
				9  => sprintf(
					$this->getGlobalWord( 'scheduled_for' ),
					date_i18n( $this->getGlobalWord( 'date_i18n' ), strtotime( $post->post_date ) )
				),
				10 => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'draft_updated_feminine' : 'draft_updated_masculine' ) . '.',
			];

			if ( $post_type_object->publicly_queryable && $this->contentSlug === $post_type ) {
				$permalink = get_permalink( $post->ID );

				$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), $this->getGlobalWord( 'view' ) . ' ' . strtolower( $this->getLabel( 'the_singular' ) ) );
				$messages[ $post_type ][1] .= $view_link;
				$messages[ $post_type ][6] .= $view_link;
				$messages[ $post_type ][9] .= $view_link;

				$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
				$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), $this->getLabel( 'the_singular' ) );
				$messages[ $post_type ][8]  .= $preview_link;
				$messages[ $post_type ][10] .= $preview_link;
			}
		}

		if ( 'Taxonomy' === $this->getCustomContentType() ) {
			$messages[ $this->contentSlug ] = [
				0 => '',
				1 => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'added_feminine' : 'added_masculine' ) . '.',
				2 => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'deleted_feminine' : 'deleted_masculine' ) . '.',
				3 => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'updated_feminine' : 'updated_masculine' ) . '.',
				4 => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'not_added_feminine' : 'not_added_masculine' ) . '.',
				5 => $this->getLabel( 'singular_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'not_updated_feminine' : 'not_updated_masculine' ) . '.',
				6 => $this->getLabel( 'plural_name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'deleted_plural_feminine' : 'deleted_plural_masculine' ) . '.',
			];
		}

		return $messages ?? [];
	}
}
