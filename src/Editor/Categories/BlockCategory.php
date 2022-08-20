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
	 * {@inheritdoc}
	 */
	public function getFilters(): array
	{
		return [ $this->getHookName() => [ 'processEditorCategory', 10, 1 ] ];
	}

	/**
	 * Add block category
	 *
	 * @return array
	 */
	public function processEditorCategory( $block_categories ): array
	{
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
