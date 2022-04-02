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
	 * {@inheritdoc}
	 */
	public function hook(): void
	{
		add_filter( $this->getHookName(), [ $this, 'finalProcess' ] );
	}

	/**
	 * Final process callback function
	 *
	 * @return array
	 */
	public function finalProcess( $block_categories ): array
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
