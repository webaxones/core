<?php

namespace Webaxones\Core\Config;

defined( 'ABSPATH' ) || exit;

/**
 * Admin menu slugs
 */
class AdminMenuSlugs
{
	/**
	 * Self instance
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Admin menu slugs
	 *
	 * @var array
	 */
	private static array $adminMenuSlugs;

	public function __construct()
	{
		self::$adminMenuSlugs = [
			'dashboard'  => 'index.php',
			'posts'      => 'edit.php',
			'pages'      => 'edit.php?post_type=page',
			'comments'   => 'edit-comments.php',
			'theme'      => 'themes.php',
			'plugins'    => 'plugins.php',
			'users'      => 'users.php',
			'management' => 'tools.php',
			'options'    => 'options-general.php',
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
	 * Get admin menu slugs values
	 *
	 * @return array
	 */
	public static function getValues(): array
	{
		self::init();
		return self::$adminMenuSlugs ?? [];
	}
}
