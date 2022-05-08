# Webaxones Core

**Entity processing**<br><br>
« An entity is an abstraction that we consider as a reality »<br>
*Le Robert*

Within this library, an entity can be:<br>

a *Content Classification* like **Custom Post Types** or **Custom Taxonomies**<br>
an *Option Page* like **(Native) Option Page** or **ACF Option Page**<br>
an *Editor Category* like **Block Category** or **Block Pattern Category**<br>
a **Custom Role**

## 1- Create a folder for your plugin

```bash
wp-content\plugins\my-example-plugin\
```

The example below integrates all the declarations in 1 single plugin for simplification but:<br>
**using a library inside a main global composer/vendor like in Bedrock allows you to make as many plugins as you want.**<br>
And it is recommended to be able to deactivate this or that functionality.

## 2- Add library

Add Webaxones Core library to the global composer if you have one:

```bash
composer require webaxones/core
```

If you don’t have one, you can initialize composer inside plugin folder with `composer init` or create a `composer.json` file manually:

```bash
{
	"name": "webaxones/my-example-plugin",
	"description": "Custom content declaration",
	"license": "GPL-2.0",
	"authors": [
	  {
		"name": "Webaxones",
		"email": "contact@webaxones.com"
	  }
	],
	"type" : "wordpress-plugin",
	"minimum-stability": "dev",
	"prefer-stable": true
}
```
Then add the Webaxones Core library to it:

```bash
composer require webaxones/core
```

## 3- Create the plugin

Add desired content declarations to your `my-example-plugin.php` file:

```php
<?php
/**
 * Plugin Name:       Example Custom Content
 * Author:            My Name
 * Text Domain:       wax-custom-content
 * Domain Path:       /languages
 */
defined( 'ABSPATH' ) || exit;

use Webaxones\Core\Entities\Entities;

Webaxones\Core\Library::init( 'webaxones-content' );

/**
 * Custom Post Type: Project
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Classification\PostType',
	'labels'   => [
		'gender'            => 'm',
		'name'              => _x( 'Projects', 'Capitalized Plural Name', 'webaxones-content' ),
		'singular_name'     => _x( 'Project', 'Capitalized Singular Name', 'webaxones-content' ),
		'parent_item_colon' => __( 'Parent project: ', 'webaxones-content' ),
		'all_items'         => __( 'All projects', 'webaxones-content' ),
		'new_item'          => __( 'New project', 'webaxones-content' ),
		'the_singular'      => __( 'The project', 'webaxones-content' ),
		'the_plural'        => __( 'The projects', 'webaxones-content' ),
	],
	'settings' => [
		'slug'          => 'project',
		'taxonomies'    => [],
		'supports'      => [ 'title', 'thumbnail', 'editor', /*'excerpt', 'author', 'comments', 'revisions', 'page-attributes', 'post-formats', 'custom-fields', 'trackbacks'*/ ],
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 7,
		'hierarchical'  => false,
		'public'        => true,
		'show_in_rest'  => true,
		'has_archive'   => true,
	],
];

/**
 * Custom Taxonomy: Project category
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Classification\Taxonomy',
	'labels'   => [
		'gender'            => 'f',
		'name'              => _x( 'Project categories', 'Capitalized Plural Name', 'webaxones-content' ),
		'singular_name'     => _x( 'Project category', 'Capitalized Singular Name', 'webaxones-content' ),
		'parent_item_colon' => __( 'Parent project category: ', 'webaxones-content' ),
		'all_items'         => __( 'All project categories', 'webaxones-content' ),
		'new_item'          => __( 'New project category', 'webaxones-content' ),
		'the_singular'      => __( 'The project category', 'webaxones-content' ),
		'the_plural'        => __( 'The project categories', 'webaxones-content' ),
	],
	'settings' => [
		'slug'              => 'project-category',
		'hierarchical'      => false,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'object_type'       => ['project'],
	],
];

/**
 * Custom native option page: Company data
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Option\OptionsPage',
	'labels'   => [
		'page_title' => _x( 'Company data', 'Option page title', 'webaxones-content' ),
		'menu_title' => _x( 'Parameters', 'Option page menu title', 'webaxones-content' ),
	],
	'settings' => [
		'slug'        => 'wax-company-settings',
		'location'    => 'top_level', /*'custom', 'dashboard', 'posts', 'pages', 'comments', 'theme', 'plugins', 'users', 'management', 'options'*/
		'parent_slug' => '', /* if location is "custom" */
		'capability'  => 'manage_options',
		'icon_url'    => 'dashicons-admin-generic',
		'position'    => 99,
	],
];

/**
 * Custom ACF option page: Projects archive
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Option\AcfOptionsPage',
	'labels'   => [
		'page_title' => _x( 'Projects archive', 'Option page title', 'webaxones-content' ),
		'menu_title' => _x( 'Projects page', 'Option page menu title', 'webaxones-content' ),
	],
	'settings' => [
		'slug'        => 'wax-projects-settings',
		'location'    => 'custom', /*'custom', 'dashboard', 'posts', 'pages', 'comments', 'theme', 'plugins', 'users', 'management', 'options'*/
		'parent_slug' => 'edit.php?post_type=project', /* if location is "custom" */
		'capability'  => 'manage_options',
		'icon_url'    => '',
		'position'    => 99,
	],
];

/**
 * Add Custom role: Owner
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Role\Role',
	'labels'   => [
		'role_name' => _x( 'Owner', 'Custom role name', 'webaxones-content' ),
	],
	'settings' => [
		'slug'                   => 'owner',
		'action'                 => 'add',
		'role_to_clone_slug'     => 'administrator',
		'capabilities_to_remove' => [
			'manage_options',
			'switch_themes',
			'remove_users',
			'activate_plugins',
			'delete_others_pages',
			'delete_site',
			'delete_pages',
			'delete_private_pages',
			'delete_published_pages',
			'delete_others_posts',
			'delete_posts',
			'delete_private_posts',
			'edit_theme_options',
			'export',
			'import',
			'edit_private_pages',
			'edit_private_posts',
			'promote_users',
			'customize',
		],
	],
];

/**
 * Remove Custom role: Test
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Role\Role',
	'labels'   => [],
	'settings' => [
		'slug'                   => 'test',
		'action'                 => 'remove',
		'role_to_clone_slug'     => '',
		'capabilities_to_remove' => [],
	],
];

/**
 * Update Custom role: Owner
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Role\Role',
	'labels'   => [
		'role_name' => _x( 'Owner', 'Custom role name', 'webaxones-content' ),
	],
	'settings' => [
		'slug'                   => 'owner',
		'action'                 => 'update',
		'role_to_clone_slug'     => 'administrator',
		'capabilities_to_remove' => [
			'manage_options',
			'switch_themes',
			'remove_users',
			'promote_users',
			'customize',
		],
	],
];

/**
 * Add custom block pattern category: Webaxones Patterns
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Editor\Categories\BlockPatternCategory',
	'labels'   => [
		'label' => _x( 'Webaxones Patterns', 'Custom block pattern name', 'webaxones-content' ),
	],
	'settings' => [
		'slug'   => 'webaxones_patterns',
		'action' => 'add',
	],
];

/**
 * Remove custom block pattern category: test
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Editor\Categories\BlockPatternCategory',
	'labels'   => [],
	'settings' => [
		'slug'   => 'test',
		'action' => 'remove',
	],
];

/**
 * Add custom block category: Custom category
 */
$declarations[] = [
	'entity'   => 'Webaxones\Core\Editor\Categories\BlockCategory',
	'labels'   => [
		'title' => _x( 'Custom category', 'Custom block category name', 'webaxones-content' ),
	],
	'settings' => [
		'slug' => 'webaxones_custom_category',
		'icon' => 'dashicons-admin-generic', /* Slug of a WordPress Dashicon or custom SVG */
	],
];

Entities::process( $declarations );

```

