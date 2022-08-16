<?php

namespace Webaxones\Core\Editor\Categories;

defined( 'ABSPATH' ) || exit;

use \DecaLog\Engine as Decalog;

/**
 * Custom block category declaration
 */
class BlockCategory extends AbstractEditorCategory
{
	/**
	 * {@inheritdoc}
	 */
	public function getHookName(): string
	{
		return 'block_categories_all';
	}

	/**
	 * Add block category
	 *
	 * @return array
	 */
	public function processCategory( $block_categories ): array
	{
		Decalog::eventsLogger( 'webaxones-entities' )->info( '« ' . $this->getSlug() . ' » Block category declared.' );
		return array_merge(
			$block_categories,
			[
				[
					'slug'  => $this->getSlug(),
					'title' => $this->args['label'],
				],
			]
		);
	}
}
