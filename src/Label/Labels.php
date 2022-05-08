<?php

namespace Webaxones\Core\Label;

use Webaxones\Core\Utils\Contracts\LabelsInterface;
use Webaxones\Core\Utils\Contracts\FilterInterface;
use Webaxones\Core\Utils\Contracts\HookInterface;

use Webaxones\Core\Utils\Concerns\OptionalSettingsTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Internationalized labels declaration
 */
class Labels implements LabelsInterface, HookInterface, FilterInterface
{
	use OptionalSettingsTrait;

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
	 * Custom content labels
	 *
	 * @var array
	 */
	protected array $labels;

	/**
	 * Custom content global words
	 *
	 * @var array
	 */
	protected array $globalWords;

	/**
	 * Custom content gender
	 *
	 * @var bool
	 */
	protected bool $contentGender;

	/**
	 * Label Constructor
	 *
	 * @param  string $classShortName
	 * @param  string $contentSlug
	 * @param  array $labels
	 * @param  array $globalWords
	 */
	public function __construct( string $classShortName, string $contentSlug, array $labels, array $globalWords )
	{
		$this->customContentType = $classShortName;
		$this->contentSlug       = $contentSlug;
		$this->labels            = $labels;
		$this->globalWords       = $globalWords;
		$this->contentGender     = array_key_exists( 'gender', $labels ) && 'f' === $labels['gender'] ? 1 : 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		if ( 'PostType' === $this->getCustomContentType() ) {
			return 'post_updated_messages';
		}

		if ( 'Taxonomy' === $this->getCustomContentType() ) {
			return 'term_updated_messages';
		}

		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFilters(): array
	{
		return [ $this->getHookName() => [ 'processMessagesLabels', 10, 1 ] ];
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
	public function processClassificationLabels( array $labels ): array
	{
		if ( array_key_exists( 'singular_name', $this->getLabels() ) && array_key_exists( 'name', $this->getLabels() ) ) {
			$labels = [
				'name'               => $this->getLabel( 'name' ),
				'singular_name'      => $this->getLabel( 'singular_name' ),
				'edit_item'          => $this->getGlobalWord( 'edit' ) . ' ' . strtolower( $this->getLabel( 'the_singular' ) ),
				'update_item'        => $this->getGlobalWord( 'update' ) . ' ' . strtolower( $this->getLabel( 'the_singular' ) ),
				'view_item'          => $this->getGlobalWord( 'view' ) . ' ' . strtolower( $this->getLabel( 'the_singular' ) ),
				'view_items'         => $this->getGlobalWord( 'view' ) . ' ' . strtolower( $this->getLabel( 'the_plural' ) ),
				'add_new_item'       => $this->getGlobalWord( 'add' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'a_feminine' : 'a_masculine' ) . ' ' . strtolower( $this->getLabel( 'new_item' ) ),
				'archives'           => $this->getGlobalWord( 'list_of' ) . ' ' . strtolower( $this->getLabel( 'name' ) ),
				'attributes'         => $this->getGlobalWord( 'attributes_of' ) . ' ' . strtolower( $this->getLabel( 'name' ) ),
				'not_found'          => $this->getGlobalWord( 'not_found' ),
				'not_found_in_trash' => $this->getGlobalWord( 'not_found_in_trash' ),
				'search_items'       => $this->getGlobalWord( 'search_items' ),
				'menu_name'          => $this->getLabel( 'name' ),
			];
		}
		return $labels;
	}

	/**
	 * {@inheritdoc}
	 */
	public function processOptionalLabels( array $labels ): array
	{
		$declarationLabels = $this->getLabels();
		unset( $declarationLabels['gender'] );
		$diff = array_diff( $declarationLabels, $labels );
		return array_merge( $labels, $diff );
	}

	/**
	 * {@inheritdoc}
	 */
	public function processLabels(): array
	{
		$labels = [];
		$labels = $this->processClassificationLabels( $labels );
		$labels = $this->processOptionalLabels( $labels );

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
				6 => $this->getLabel( 'name' ) . ' ' . $this->getGlobalWord( $this->getGender() ? 'deleted_plural_feminine' : 'deleted_plural_masculine' ) . '.',
			];
		}

		return $messages ?? [];
	}
}
