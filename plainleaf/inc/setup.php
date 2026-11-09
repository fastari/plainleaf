<?php
/**
 * Theme setup: supports, styles, font preloading, pattern categories.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme supports and editor styles.
 */
function plainleaf_setup() {
	load_theme_textdomain( 'plainleaf', PLAINLEAF_DIR . '/languages' );

	add_editor_style( 'style.css' );

	// Keep the inserter focused on this theme's patterns, not the generic core set.
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'plainleaf_setup' );

/**
 * Do not load remote patterns from the wordpress.org pattern directory.
 * Keeps the editor calm and avoids an external request.
 */
add_filter( 'should_load_remote_block_patterns', '__return_false' );

/**
 * Front-end stylesheet.
 */
function plainleaf_enqueue_styles() {
	wp_enqueue_style(
		'plainleaf',
		get_stylesheet_uri(),
		array(),
		PLAINLEAF_VERSION
	);
	// Lets WordPress inline the file when it is small enough, which saves a request.
	wp_style_add_data( 'plainleaf', 'path', get_stylesheet_directory() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'plainleaf_enqueue_styles' );

/**
 * Preload the fonts used above the fold (titles, reading text and interface)
 * so text renders in the right face sooner. Follows whichever font pairing is
 * active in Styles > Typography. Italic files load on demand.
 */
function plainleaf_preload_fonts() {
	$families = wp_get_global_settings( array( 'typography', 'fontFamilies' ) );
	if ( ! is_array( $families ) ) {
		return;
	}
	$urls = array();
	// Later origins (custom, then theme) override earlier ones for the same slug.
	$by_slug = array();
	foreach ( array( 'default', 'theme', 'custom' ) as $origin ) {
		foreach ( isset( $families[ $origin ] ) && is_array( $families[ $origin ] ) ? $families[ $origin ] : array() as $family ) {
			if ( isset( $family['slug'] ) ) {
				$by_slug[ $family['slug'] ] = $family;
			}
		}
	}
	foreach ( array( 'display', 'serif', 'sans' ) as $slug ) {
		if ( empty( $by_slug[ $slug ]['fontFace'] ) || ! is_array( $by_slug[ $slug ]['fontFace'] ) ) {
			continue;
		}
		foreach ( $by_slug[ $slug ]['fontFace'] as $face ) {
			if ( isset( $face['fontStyle'] ) && 'normal' !== $face['fontStyle'] ) {
				continue;
			}
			$src = is_array( $face['src'] ?? null ) ? reset( $face['src'] ) : ( $face['src'] ?? '' );
			if ( ! is_string( $src ) || '' === $src ) {
				continue;
			}
			$urls[] = 0 === strpos( $src, 'file:./' ) ? get_theme_file_uri( substr( $src, 7 ) ) : $src;
			break;
		}
	}
	foreach ( array_unique( $urls ) as $url ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $url ) );
	}
}
add_action( 'wp_head', 'plainleaf_preload_fonts', 1 );

/**
 * Pattern categories for this theme.
 */
function plainleaf_pattern_categories() {
	register_block_pattern_category(
		'plainleaf',
		array(
			'label'       => __( 'Plainleaf', 'plainleaf' ),
			'description' => __( 'Sections and layouts made for this site.', 'plainleaf' ),
		)
	);
	register_block_pattern_category(
		'plainleaf-pages',
		array(
			'label'       => __( 'Plainleaf pages', 'plainleaf' ),
			'description' => __( 'Full page layouts.', 'plainleaf' ),
		)
	);
}
add_action( 'init', 'plainleaf_pattern_categories' );

/**
 * Block styles that cannot be expressed as theme.json block style variations.
 */
function plainleaf_block_styles() {
	// From Twenty Twenty-Five.
	register_block_style(
		'core/list',
		array(
			'name'         => 'checkmark-list',
			'label'        => __( 'Checkmark', 'plainleaf' ),
			'inline_style' => 'ul.is-style-checkmark-list{list-style-type:"\2713";}ul.is-style-checkmark-list li{padding-inline-start:1ch;}',
		)
	);
}
add_action( 'init', 'plainleaf_block_styles' );

/**
 * Optional: no RSS or Atom feeds (Appearance > Theme settings > RSS feeds).
 * When turned on, feed addresses (/feed/, /comments/feed/, category feeds and so
 * on) send people to the Blog page, and the feed <link> tags are left out.
 */
function plainleaf_no_feed_support() {
	if ( ! get_option( 'plainleaf_disable_feeds', false ) ) {
		return;
	}
	remove_theme_support( 'automatic-feed-links' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	foreach ( array( 'do_feed', 'do_feed_rdf', 'do_feed_rss', 'do_feed_rss2', 'do_feed_atom', 'do_feed_rss2_comments', 'do_feed_atom_comments' ) as $action ) {
		add_action( $action, 'plainleaf_redirect_feeds', 1 );
	}
}
add_action( 'after_setup_theme', 'plainleaf_no_feed_support', 20 );

/**
 * Redirect a feed request to the Blog page.
 */
function plainleaf_redirect_feeds() {
	wp_safe_redirect( plainleaf_blog_url(), 301 );
	exit;
}

/**
 * Show pages inside their template in the editor (Appearance > Editor and the
 * page editor), so the home page preview shows the real home layout instead of
 * an empty page. The page content stays editable; "Show template" in the
 * editor's options still switches it off.
 */
function plainleaf_page_editor_mode() {
	add_post_type_support( 'page', 'editor', array( 'default-mode' => 'template-locked' ) );
}
add_action( 'init', 'plainleaf_page_editor_mode', 20 );
