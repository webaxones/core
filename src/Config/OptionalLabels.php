<?php

namespace Webaxones\Core\Config;

defined( 'ABSPATH' ) || exit;

/**
 * Admin menu slugs
 */
class OptionalLabels
{
	/**
	 * Self instance
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Optional labels
	 *
	 * @var array
	 */
	private static array $optionalLabels;

	public function __construct()
	{
		self::$optionalLabels = [
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
			'role_name',
		];
	}

	/**
	 * Singleton pattern
	 *
	 * @return self
	 */
	private static function init(): self
	{
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get optional labels values
	 *
	 * @return array
	 */
	public static function getValues(): array
	{
		self::init();
		return self::$optionalLabels ?? [];
	}
}
