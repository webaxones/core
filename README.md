# Webaxones Core

Custom declarations

## 1- Create a folder for your plugin

```bash
wp-content\plugins\my-example-plugin\
```

## 2- Add library

Add Webaxones Core library to the global composer if you have one:

```bash
composer require wax/custom-content
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
composer require wax/custom-content
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

// In this case, library is inside Bedrock root vendor so we just create a constant inside Config/Application.php just after $root_dir definition:
// Config::define('WAX_ROOT_DIR', $root_dir);
// So that we can use it now to load our classes inside plugin(s)

require_once wp_normalize_path( WAX_ROOT_DIR ) . '/vendor/autoload.php';

use Webaxones\Core\I18n\I18n;
use Webaxones\Core\Utils\ContentFactory;

$i18n = new I18n();
$i18n->loadPluginTextdomain( 'wax-custom-content' );

/**
 * Custom Post Type: Project
 */
$customContents[] = new ContentFactory(
	'PostType',
	[
		'labels'   => [
			'gender'            => 'm',
			'plural_name'       => _x( 'Projects', 'Capitalized Plural Name', 'wax-custom-content' ),
			'singular_name'     => _x( 'Project', 'Capitalized Singular Name', 'wax-custom-content' ),
			'parent_item_colon' => __( 'Parent project: ', 'wax-custom-content' ),
			'all_items'         => __( 'All projects', 'wax-custom-content' ),
			'new_item'          => __( 'New project', 'wax-custom-content' ),
			'the_singular'      => __( 'The project', 'wax-custom-content' ),
			'the_plural'        => __( 'The projects', 'wax-custom-content' ),
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
	]
);

/**
 * Custom Taxonomy: Project category
 */
$customContents[] = new ContentFactory(
	'Taxonomy',
	[
		'labels'      => [
			'gender'            => 'f',
			'plural_name'       => _x( 'Project categories', 'Capitalized Plural Name', 'wax-custom-content' ),
			'singular_name'     => _x( 'Project category', 'Capitalized Singular Name', 'wax-custom-content' ),
			'parent_item_colon' => __( 'Parent project category: ', 'wax-custom-content' ),
			'all_items'         => __( 'All project categories', 'wax-custom-content' ),
			'new_item'          => __( 'New project category', 'wax-custom-content' ),
			'the_singular'      => __( 'The project category', 'wax-custom-content' ),
			'the_plural'        => __( 'The project categories', 'wax-custom-content' ),
		],
		'settings'    => [
			'slug'              => 'project-category',
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'object_type'       => ['project'],
		],
	]
);

/**
 * Custom native option page: Company data
 */
$customContents[] = new ContentFactory(
	'OptionsPage',
	[
		'labels'   => [
			'page_title' => _x( 'Company data', 'Option page title', 'wax-custom-content' ),
			'menu_title' => _x( 'Parameters', 'Option page menu title', 'wax-custom-content' ),
		],
		'settings' => [
			'slug'        => 'wax-company-settings',
			'location'    => 'top_level', /*'custom', 'dashboard', 'posts', 'pages', 'comments', 'theme', 'plugins', 'users', 'management', 'options'*/
			'parent_slug' => '', /* if location is "custom" */
			'capability'  => 'manage_options',
			'icon_url'    => 'dashicons-admin-generic',
			'position'    => 99,
		],
	]
);

/**
 * Custom ACF option page: Projects archive
 */
$customContents[] = new ContentFactory(
	'AcfOptionsPage',
	[
		'labels'   => [
			'page_title' => _x( 'Projects archive', 'Option page title', 'wax-custom-content' ),
			'menu_title' => _x( 'Projects page', 'Option page menu title', 'wax-custom-content' ),
		],
		'settings' => [
			'slug'        => 'wax-projects-settings',
			'location'    => 'custom', /*'custom', 'dashboard', 'posts', 'pages', 'comments', 'theme', 'plugins', 'users', 'management', 'options'*/
			'parent_slug' => 'edit.php?post_type=project', /* if location is "custom" */
			'capability'  => 'manage_options',
			'icon_url'    => '',
			'position'    => 99,
		],
	]
);

array_walk(
	$customContents,
	function( $customContent )
	{
		try {
			$content = $customContent->createCustomContent();
			$content->hook();
		} catch ( Exception $e ) {
			error_log( $e->getMessage() );
		}
	}
);

```

