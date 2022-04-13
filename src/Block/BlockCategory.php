<?php

namespace Webaxones\Core\Block;

defined( 'ABSPATH' ) || exit;

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
		return array_merge(
			$block_categories,
			[
				[
					'slug' => $this->getSlug(),
				],
				$this->args,
				[
					'icon' => $this->settings['icon'],
				],
			]
		);
	}
}
