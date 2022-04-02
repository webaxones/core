<?php

namespace Webaxones\Core\Config;

defined( 'ABSPATH' ) || exit;

/**
 * Admin menu slugs
 */
class AdminMenuSlugs
{
	/**
	 * Admin menu slugs
	 *
	 * @var array
	 */
	public static array $adminMenuSlugs = [
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
