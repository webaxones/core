<?php

namespace Webaxones\Core\Editor\Categories;

defined( 'ABSPATH' ) || exit;

use \DecaLog\Engine as Decalog;

/**
 * Handles creating, deleting custom Block category
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
		return [ $this->getHookName() => [ 'processBlockCategory' ] ];
	}

	/**
	 * Add block category
	 *
	 * @return array
	 */
	public function processBlockCategory( $block_categories ): array
	{
		return array_merge(
			$block_categories,
			[
				[
					'slug'  => $this->getSlug(),
					'title' => $this->args['title'],
				],
			]
		);
	}
}
