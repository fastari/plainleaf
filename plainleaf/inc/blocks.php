<?php
/**
 * Theme blocks.
 *
 * plainleaf/wordmark renders the logo chosen in Appearance > Theme settings (or
 * the site title) at request time, so no image URLs end up saved in templates
 * and the logo keeps working when the site moves between local, staging and live.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme blocks.
 */
function plainleaf_register_blocks() {
	register_block_type( PLAINLEAF_DIR . '/blocks/wordmark' );
	register_block_type( PLAINLEAF_DIR . '/blocks/newsletter-form' );
	register_block_type( PLAINLEAF_DIR . '/blocks/author-photo' );
}
add_action( 'init', 'plainleaf_register_blocks' );

/**
 * Pass the logo, title and display mode to the editor so it can preview the
 * block without a server round trip.
 */
function plainleaf_wordmark_editor_data() {
	$logo = plainleaf_logo_id();
	wp_add_inline_script(
		generate_block_asset_handle( 'plainleaf/wordmark', 'editorScript' ),
		'window.plainleafWordmark = ' . wp_json_encode(
			array(
				'logo'        => $logo ? (string) wp_get_attachment_image_url( $logo, 'medium' ) : '',
				'name'        => get_bloginfo( 'name' ),
				'mode'        => get_option( 'plainleaf_logo_mode', 'auto' ),
				'settingsUrl' => admin_url( 'themes.php?page=pl-settings' ),
			)
		) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', 'plainleaf_wordmark_editor_data' );

/**
 * Author photo preview data for the editor.
 */
function plainleaf_author_photo_editor_data() {
	$id    = (int) get_option( 'plainleaf_author_photo', 0 );
	$photo = $id ? wp_get_attachment_image_url( $id, 'thumbnail' ) : '';
	if ( ! $photo ) {
		// Editor preview only: an example photo until one is chosen. Visitors never see it.
		$photo = get_theme_file_uri( 'assets/images/demo-portrait.webp' );
	}
	wp_add_inline_script(
		generate_block_asset_handle( 'plainleaf/author-photo', 'editorScript' ),
		'window.plainleafAuthor = ' . wp_json_encode(
			array(
				'photo'       => $photo,
				'settingsUrl' => admin_url( 'themes.php?page=pl-settings' ),
			)
		) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', 'plainleaf_author_photo_editor_data' );

/**
 * URL of a file in assets/images/brand, with a version string for cache busting.
 *
 * @param string $file File name.
 * @return string
 */
function plainleaf_brand_asset_uri( $file ) {
	return add_query_arg( 'ver', PLAINLEAF_VERSION, get_theme_file_uri( 'assets/images/brand/' . $file ) );
}

/**
 * Mark the navigation link for the current page with aria-current="page".
 *
 * Core only does this for links tied to a post ID. The theme's default menus use
 * plain URLs (so they work before any pages exist), so compare paths instead.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function plainleaf_navigation_link_current( $block_content, $block ) {
	if ( empty( $block['attrs']['url'] ) || false !== strpos( $block_content, 'aria-current' ) ) {
		return $block_content;
	}

	$link_path    = trailingslashit( (string) wp_parse_url( $block['attrs']['url'], PHP_URL_PATH ) );
	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$current_path = trailingslashit( (string) wp_parse_url( $request_uri, PHP_URL_PATH ) );
	$link_host    = wp_parse_url( $block['attrs']['url'], PHP_URL_HOST );

	if ( $link_host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $link_host ) {
		return $block_content;
	}

	$is_current = $link_path === $current_path;

	// Treat posts and blog pages as being inside "Blog".
	if ( ! $is_current && (int) get_option( 'page_for_posts' ) ) {
		$blog_path = trailingslashit( (string) wp_parse_url( get_permalink( (int) get_option( 'page_for_posts' ) ), PHP_URL_PATH ) );
		$is_current = $link_path === $blog_path && ( is_singular( 'post' ) || is_category() || is_tag() || ( is_home() && ! is_front_page() ) );
	}

	if ( ! $is_current ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag( 'li' ) ) {
		$tags->add_class( 'current-menu-item' );
	}
	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'aria-current', 'page' );
	}
	return $tags->get_updated_html();
}
add_filter( 'render_block_core/navigation-link', 'plainleaf_navigation_link_current', 10, 2 );

/**
 * URL for a topic category: the real term link when the category exists,
 * otherwise the default /category/slug/ address.
 *
 * @param string $slug Category slug.
 * @return string
 */
function plainleaf_category_url( $slug ) {
	$term = get_category_by_slug( $slug );
	if ( $term ) {
		$link = get_category_link( $term );
		if ( $link ) {
			return $link;
		}
	}
	return home_url( '/category/' . $slug . '/' );
}

/**
 * URL for the blog index: the page set as "Posts page", or /blog/.
 *
 * @return string
 */
function plainleaf_blog_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );
	return $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/blog/' );
}

/**
 * Theme query loops. A Query Loop block whose class includes one of these gets
 * its query adjusted on the server, so templates don't depend on IDs that differ
 * between sites:
 *   - pl-related:    other posts from the current post's topics (categories),
 *                     newest first, never the current post. Hidden when empty.
 *   - pl-start-here: posts tagged "start-here", oldest first (the reading order).
 *
 * @param array    $query Query vars built by the Query Loop block.
 * @param WP_Block $block The Post Template block being rendered.
 * @return array
 */
function plainleaf_theme_query_vars( $query, $block ) {
	unset( $block );
	$class = isset( $GLOBALS['plainleaf_query_class'] ) ? (string) $GLOBALS['plainleaf_query_class'] : '';
	if ( false !== strpos( $class, 'pl-related' ) ) {
		$post_id = get_queried_object_id();
		if ( $post_id ) {
			$query['post__not_in']        = array( $post_id );
			$query['ignore_sticky_posts'] = true;
			$categories                   = wp_get_post_categories( $post_id );
			if ( $categories ) {
				$query['category__in'] = $categories;
			}
		}
	} elseif ( false !== strpos( $class, 'pl-start-here' ) ) {
		$query['tag']                 = 'start-here';
		$query['orderby']             = 'date';
		$query['order']               = 'ASC';
		$query['ignore_sticky_posts'] = true;
	}
	return $query;
}
add_filter( 'query_loop_block_query_vars', 'plainleaf_theme_query_vars', 10, 2 );

/**
 * Remember the class of the Query block being rendered (see above).
 *
 * @param string|null $pre_render Short-circuit value.
 * @param array       $parsed     Parsed block.
 * @return string|null
 */
function plainleaf_query_class_on( $pre_render, $parsed ) {
	if ( 'core/query' === $parsed['blockName'] ) {
		$GLOBALS['plainleaf_query_class'] = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';
	}
	return $pre_render;
}
add_filter( 'pre_render_block', 'plainleaf_query_class_on', 10, 2 );

/**
 * Forget the class after the Query block has rendered, and hide the related
 * posts section when it found nothing.
 *
 * @param string $content Rendered block.
 * @param array  $parsed  Parsed block.
 * @return string
 */
function plainleaf_query_class_off( $content, $parsed ) {
	if ( 'core/query' !== $parsed['blockName'] ) {
		return $content;
	}
	$class                      = isset( $parsed['attrs']['className'] ) ? $parsed['attrs']['className'] : '';
	$GLOBALS['plainleaf_query_class'] = '';
	if ( false !== strpos( $class, 'pl-related' ) && false === strpos( $content, '<li' ) ) {
		return '';
	}
	return $content;
}
add_filter( 'render_block', 'plainleaf_query_class_off', 10, 2 );

/**
 * Story hook: a Post Excerpt block with the class "pl-dek" shows only a
 * hand-written excerpt, never one generated from the post content (which would
 * repeat the first paragraph right above it).
 *
 * @param string $content Rendered block.
 * @param array  $parsed  Parsed block.
 * @return string
 */
function plainleaf_dek_only_manual_excerpt( $content, $parsed ) {
	if ( isset( $parsed['attrs']['className'] ) && false !== strpos( $parsed['attrs']['className'], 'pl-dek' ) && ! has_excerpt() ) {
		return '';
	}
	return $content;
}
add_filter( 'render_block_core/post-excerpt', 'plainleaf_dek_only_manual_excerpt', 10, 2 );

/**
 * Mark the topic filter link for the page being viewed.
 *
 * @param string $slug Category slug, or '' for "All".
 * @return string HTML attributes.
 */
function plainleaf_filter_current_attr( $slug ) {
	$current = '' === $slug ? ( is_home() && ! is_front_page() ) : is_category( $slug );
	return $current ? ' aria-current="page" class="is-current"' : '';
}
