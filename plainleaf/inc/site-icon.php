<?php
/**
 * Fallback site icon.
 *
 * WordPress normally prints the site icon set under Settings > General (or the
 * Site Editor). Until one is uploaded there, print the theme's own icon files
 * so browsers and phones still get the brand icon.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print icon links when no site icon has been set.
 */
function plainleaf_fallback_site_icon() {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" sizes="32x32" type="image/png">' . "\n", esc_url( plainleaf_brand_asset_uri( 'icon-32.png' ) ) );
	printf( '<link rel="icon" href="%s" sizes="192x192" type="image/png">' . "\n", esc_url( plainleaf_brand_asset_uri( 'icon-192.png' ) ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( plainleaf_brand_asset_uri( 'apple-touch-icon.png' ) ) );
}
add_action( 'wp_head', 'plainleaf_fallback_site_icon', 99 );
add_action( 'admin_head', 'plainleaf_fallback_site_icon', 99 );
