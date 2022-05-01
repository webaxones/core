<?php
namespace Webaxones\Core\Config;

defined( 'ABSPATH' ) || exit;

/**
 * Global words used in labels: no changes needed
 */
class GlobalWords
{
	/**
	 * Global words used in labels: no changes needed
	 *
	 * @var array
	 */
	protected static array $globalWords;

	private static function init(): array
	{
		self::$globalWords = [
			'view'                           => _x( 'View', 'Infinitive verb', 'webaxones-core' ),
			'edit'                           => _x( 'Edit', 'Infinitive verb', 'webaxones-core' ),
			'update'                         => _x( 'Update', 'Infinitive verb', 'webaxones-core' ),
			'updated_masculine'              => _x( 'updated', 'Masculine gender', 'webaxones-core' ),
			'updated_feminine'               => _x( 'updated', 'Feminine gender', 'webaxones-core' ),
			'a_masculine'                    => _x( 'a', 'Masculine indefinite article', 'webaxones-core' ),
			'a_feminine'                     => _x( 'a', 'Feminine indefinite article', 'webaxones-core' ),
			'a_plural'                       => _x( 'a', 'Plural indefinite article', 'webaxones-core' ),
			'not_updated_masculine'          => _x( 'not updated', 'Masculine gender', 'webaxones-core' ),
			'not_updated_feminine'           => _x( 'not updated', 'Feminine gender', 'webaxones-core' ),
			'draft_updated_masculine'        => _x( 'draft updated', 'Masculine gender', 'webaxones-core' ),
			'draft_updated_feminine'         => _x( 'draft updated', 'Feminine gender', 'webaxones-core' ),
			'deleted_masculine'              => _x( 'deleted', 'Masculine gender', 'webaxones-core' ),
			'deleted_feminine'               => _x( 'deleted', 'Feminine gender', 'webaxones-core' ),
			'deleted_plural_masculine'       => _x( 'deleted', 'Plural masculine gender', 'webaxones-core' ),
			'deleted_plural_feminine'        => _x( 'deleted', 'Plural feminine gender', 'webaxones-core' ),
			'added_masculine'                => _x( 'added', 'Masculine gender', 'webaxones-core' ),
			'added_feminine'                 => _x( 'added', 'Feminine gender', 'webaxones-core' ),
			'not_added_masculine'            => _x( 'not added', 'Masculine gender', 'webaxones-core' ),
			'not_added_feminine'             => _x( 'not added', 'Feminine gender', 'webaxones-core' ),
			'published_masculine'            => _x( 'published', 'Masculine gender', 'webaxones-core' ),
			'published_feminine'             => _x( 'published', 'Feminine gender', 'webaxones-core' ),
			'saved_masculine'                => _x( 'saved', 'Masculine gender', 'webaxones-core' ),
			'saved_feminine'                 => _x( 'saved', 'Feminine gender', 'webaxones-core' ),
			'custom_field_updated'           => __( 'Custom field updated', 'webaxones-core' ),
			/* translators: %s: date and time of the revision */
			'restored_to_revision_masculine' => _x( 'restored to revision from %s', 'Masculine gender', 'webaxones-core' ),
			/* translators: %s: date and time of the revision */
			'restored_to_revision_feminine'  => _x( 'restored to revision from %s', 'Feminine gender', 'webaxones-core' ),
			// translators: Publish box date format, see http://php.net/date
			'scheduled_for_masculine'        => _x( 'scheduled for: <strong>%1$s</strong>.', 'Masculine gender', 'webaxones-core' ),
			// translators: Publish box date format, see http://php.net/date
			'scheduled_for_feminine'         => _x( 'scheduled for: <strong>%1$s</strong>.', 'Feminine gender', 'webaxones-core' ),
			'date_i18n'                      => __( 'M j, Y @ G:i', 'webaxones-core' ),
			'submitted_masculine'            => _x( 'submitted', 'Masculine gender', 'webaxones-core' ),
			'submitted_feminine'             => _x( 'submitted', 'Feminine gender', 'webaxones-core' ),
			'add'                            => _x( 'Add', 'Infinitive verb', 'webaxones-core' ),
			'list_of'                        => _x( 'List of', '« List of Custom content »: Archive in nav menus', 'webaxones-core' ),
			'attributes_of'                  => _x( 'Attributes of', '« Attributes of Custom content »: Attributes meta box title', 'webaxones-core' ),
			'not_found'                      => _x( 'No item found', '« item »: Custom content item', 'webaxones-core' ),
			'not_found_in_trash'             => _x( 'No item found in trash', '« item »: Custom content item', 'webaxones-core' ),
			'search_items'                   => _x( 'Search', 'Infinitive verb', 'webaxones-core' ),
		];

		return self::$globalWords;
	}

	/**
	 * Get global words values.
	 *
	 * @return array
	 */
	public static function getValues(): array
	{
		if ( ! is_textdomain_loaded( 'webaxones-core' ) && defined( 'WP_CONTENT_DIR' ) ) {
			load_textdomain( 'webaxones-core', WP_CONTENT_DIR . '\cache\webaxones\assets\languages\webaxones-core-fr_FR.mo' );
		}

		return self::$globalWords ?? self::init();
	}
}

