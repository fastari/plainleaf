<?php
/**
 * Plainleaf functions and definitions.
 *
 * The theme is a fork of Twenty Twenty-Five (GPLv2 or later) by the WordPress team.
 * Almost everything is configured in theme.json; this file only wires up what
 * theme.json cannot do on its own.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PLAINLEAF_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );
define( 'PLAINLEAF_DIR', get_template_directory() );

require PLAINLEAF_DIR . '/inc/setup.php';
require PLAINLEAF_DIR . '/inc/blocks.php';
require PLAINLEAF_DIR . '/inc/newsletter.php';
require PLAINLEAF_DIR . '/inc/settings.php';
require PLAINLEAF_DIR . '/inc/site-text.php';
require PLAINLEAF_DIR . '/inc/llms-txt.php';
require PLAINLEAF_DIR . '/inc/listen.php';
require PLAINLEAF_DIR . '/inc/hero-media.php';
require PLAINLEAF_DIR . '/inc/updater.php';
require PLAINLEAF_DIR . '/inc/site-icon.php';
