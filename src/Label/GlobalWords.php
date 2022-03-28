<?php

namespace Webaxones\Core\Label;

defined( 'ABSPATH' ) || exit;

/**
 * Global words used in labels: no changes needed
 */
class GlobalWords
{
	/**
	 * Self instance
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Global words used in labels: no changes needed
	 *
	 * @var array
	 */
	private static array $globalWords;

	public function __construct()
	{
		self::$globalWords = [
			'view'                           => _x( 'View', 'Infinitive verb', 'wax-custom-content' ),
			'edit'                           => _x( 'Edit', 'Infinitive verb', 'wax-custom-content' ),
			'update'                         => _x( 'Update', 'Infinitive verb', 'wax-custom-content' ),
			'updated_masculine'              => _x( 'updated', 'Masculine gender', 'wax-custom-content' ),
			'updated_feminine'               => _x( 'updated', 'Feminine gender', 'wax-custom-content' ),
			'a_masculine'                    => _x( 'a', 'Masculine indefinite article', 'wax-custom-content' ),
			'a_feminine'                     => _x( 'a', 'Feminine indefinite article', 'wax-custom-content' ),
			'a_plural'                       => _x( 'a', 'Plural indefinite article', 'wax-custom-content' ),
			'not_updated_masculine'          => _x( 'not updated', 'Masculine gender', 'wax-custom-content' ),
			'not_updated_feminine'           => _x( 'not updated', 'Feminine gender', 'wax-custom-content' ),
			'draft_updated_masculine'        => _x( 'draft updated', 'Masculine gender', 'wax-custom-content' ),
			'draft_updated_feminine'         => _x( 'draft updated', 'Feminine gender', 'wax-custom-content' ),
			'deleted_masculine'              => _x( 'deleted', 'Masculine gender', 'wax-custom-content' ),
			'deleted_feminine'               => _x( 'deleted', 'Feminine gender', 'wax-custom-content' ),
			'deleted_plural_masculine'       => _x( 'deleted', 'Plural masculine gender', 'wax-custom-content' ),
			'deleted_plural_feminine'        => _x( 'deleted', 'Plural feminine gender', 'wax-custom-content' ),
			'added_masculine'                => _x( 'added', 'Masculine gender', 'wax-custom-content' ),
			'added_feminine'                 => _x( 'added', 'Feminine gender', 'wax-custom-content' ),
			'not_added_masculine'            => _x( 'not added', 'Masculine gender', 'wax-custom-content' ),
			'not_added_feminine'             => _x( 'not added', 'Feminine gender', 'wax-custom-content' ),
			'published_masculine'            => _x( 'published', 'Masculine gender', 'wax-custom-content' ),
			'published_feminine'             => _x( 'published', 'Feminine gender', 'wax-custom-content' ),
			'saved_masculine'                => _x( 'saved', 'Masculine gender', 'wax-custom-content' ),
			'saved_feminine'                 => _x( 'saved', 'Feminine gender', 'wax-custom-content' ),
			'custom_field_updated'           => __( 'Custom field updated', 'wax-custom-content' ),
			/* translators: %s: date and time of the revision */
			'restored_to_revision_masculine' => _x( 'restored to revision from %s', 'Masculine gender', 'wax-custom-content' ),
			/* translators: %s: date and time of the revision */
			'restored_to_revision_feminine'  => _x( 'restored to revision from %s', 'Feminine gender', 'wax-custom-content' ),
			// translators: Publish box date format, see http://php.net/date
			'scheduled_for_masculine'        => _x( 'scheduled for: <strong>%1$s</strong>.', 'Masculine gender', 'wax-custom-content' ),
			// translators: Publish box date format, see http://php.net/date
			'scheduled_for_feminine'         => _x( 'scheduled for: <strong>%1$s</strong>.', 'Feminine gender', 'wax-custom-content' ),
			'date_i18n'                      => __( 'M j, Y @ G:i', 'wax-custom-content' ),
			'submitted_masculine'            => _x( 'submitted', 'Masculine gender', 'wax-custom-content' ),
			'submitted_feminine'             => _x( 'submitted', 'Feminine gender', 'wax-custom-content' ),
			'add'                            => _x( 'Add', 'Infinitive verb', 'wax-custom-content' ),
			'list_of'                        => _x( 'List of', '« List of Custom content »: Archive in nav menus', 'wax-custom-content' ),
			'attributes_of'                  => _x( 'Attributes of', '« Attributes of Custom content »: Attributes meta box title', 'wax-custom-content' ),
			'not_found'                      => _x( 'No item found', '« item »: Custom content item', 'wax-custom-content' ),
			'not_found_in_trash'             => _x( 'No item found in trash', '« item »: Custom content item', 'wax-custom-content' ),
			'search_items'                   => _x( 'Search', 'Infinitive verb', 'wax-custom-content' ),
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
	 * Get global words values.
	 *
	 * @return array
	 */
	public static function getValues(): array
	{
		self::init();
		return self::$globalWords ?? [];
	}
}
