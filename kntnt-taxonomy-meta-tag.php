<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt's Taxonomy Meta Tag
 * Plugin URI:        https://github.com/Kntnt/kntnt-taxonomy-meta-tag
 * Description:       Outputs in meta tags the current post's terms of selected taxonomies.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       kntnt-taxonomy-meta-tag
 * Domain Path:       /languages
 */

namespace Kntnt\Taxonomy_Meta_Tag;

defined( 'WPINC' ) || die;

require_once __DIR__ . '/classes/class-plugin.php';

new Plugin( [
	'index' => [ 'Meta_Tag_Writer' ],
	'admin' => [ 'Settings' ],
] );
