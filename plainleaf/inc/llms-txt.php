<?php
/**
 * /llms.txt: a plain-text guide to the site for AI tools (https://llmstxt.org).
 *
 * Built on request from what the site already says, so it never goes stale:
 *   - the Site Title and Tagline (Settings > General),
 *   - a summary line (Appearance > Site text > AI tools, or the line under the
 *     homepage headline),
 *   - published pages, the topics (Posts > Categories) and published posts, each
 *     with its excerpt.
 * Pages and posts hidden from search engines are left out (The SEO Framework,
 * Yoast SEO, or the plainleaf_llms_txt_hidden filter), so "Check your inbox" and
 * "You're in" never appear.
 *
 * A real llms.txt file in the site folder takes priority over this, because the
 * web server answers with the file before WordPress runs. The Hostinger Tools
 * plugin writes one, so turn off its LLMs.txt setting to use this version.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether another plugin's llms.txt file is in the site folder (and so is the one
 * visitors get).
 *
 * @return bool
 */
function plainleaf_llms_txt_file_exists() {
	return file_exists( ABSPATH . 'llms.txt' );
}

/**
 * Whether a page or post is hidden from search engines, and so from llms.txt.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function plainleaf_llms_txt_hidden( $post_id ) {
	$hidden = '1' === (string) get_post_meta( $post_id, '_genesis_noindex', true )
		|| '1' === (string) get_post_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', true )
		|| post_password_required( $post_id );
	return (bool) apply_filters( 'plainleaf_llms_txt_hidden', $hidden, $post_id );
}

/**
 * One line of plain text, safe inside a Markdown list item.
 *
 * @param string $text Text, possibly with HTML.
 * @return string
 */
function plainleaf_llms_txt_clean( $text ) {
	$text = html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	return trim( preg_replace( '/\s+/u', ' ', $text ) );
}

/**
 * A page's or post's description: its excerpt, or the first words of its text.
 *
 * @param WP_Post $post Post.
 * @return string
 */
function plainleaf_llms_txt_summary( $post ) {
	if ( has_excerpt( $post ) ) {
		return plainleaf_llms_txt_clean( get_the_excerpt( $post ) );
	}
	$text = plainleaf_llms_txt_clean( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ) );
	return '' === $text ? '' : wp_trim_words( $text, 30, '…' );
}

/**
 * A Markdown list item: [Title](url): description.
 *
 * @param string $title Title.
 * @param string $url   Address.
 * @param string $desc  Description, may be empty.
 * @return string
 */
function plainleaf_llms_txt_item( $title, $url, $desc ) {
	$title = str_replace( array( '[', ']' ), array( '(', ')' ), plainleaf_llms_txt_clean( $title ) );
	return '- [' . $title . '](' . esc_url_raw( $url ) . ')' . ( '' !== $desc ? ': ' . $desc : '' ) . "\n";
}

/**
 * The file's text.
 *
 * @return string
 */
function plainleaf_llms_txt_content() {
	$out = '# ' . plainleaf_llms_txt_clean( get_bloginfo( 'name' ) ) . "\n\n";

	$tagline = plainleaf_llms_txt_clean( get_bloginfo( 'description' ) );
	if ( '' !== $tagline ) {
		$out .= '> ' . $tagline . "\n\n";
	}
	$summary = plainleaf_text( 'llms_summary' );
	if ( '' === $summary ) {
		$summary = plainleaf_text( 'hero_subtitle' );
	}
	if ( '' !== $summary ) {
		$out .= plainleaf_llms_txt_clean( $summary ) . "\n\n";
	}

	$front = (int) get_option( 'page_on_front' );
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
	usort(
		$pages,
		function ( $a, $b ) use ( $front ) {
			return (int) ( $b->ID === $front ) - (int) ( $a->ID === $front );
		}
	);
	$lines = '';
	foreach ( $pages as $page ) {
		if ( ! plainleaf_llms_txt_hidden( $page->ID ) ) {
			$lines .= plainleaf_llms_txt_item( get_the_title( $page ), get_permalink( $page ), plainleaf_llms_txt_summary( $page ) );
		}
	}
	if ( '' !== $lines ) {
		$out .= '## ' . plainleaf_text( 'llms_pages' ) . "\n\n" . $lines . "\n";
	}

	$lines = '';
	foreach ( plainleaf_topics() as $topic ) {
		$lines .= plainleaf_llms_txt_item( $topic['name'], $topic['url'], plainleaf_llms_txt_clean( $topic['description'] ) );
	}
	if ( '' !== $lines ) {
		$out .= '## ' . plainleaf_text( 'llms_topics' ) . "\n\n" . $lines . "\n";
	}

	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => (int) apply_filters( 'plainleaf_llms_txt_post_limit', 100 ),
		)
	);
	$lines = '';
	foreach ( $posts as $post ) {
		if ( ! plainleaf_llms_txt_hidden( $post->ID ) ) {
			$lines .= plainleaf_llms_txt_item( get_the_title( $post ), get_permalink( $post ), plainleaf_llms_txt_summary( $post ) );
		}
	}
	if ( '' !== $lines ) {
		$out .= '## ' . plainleaf_text( 'llms_posts' ) . "\n\n" . $lines . "\n";
	}

	return (string) apply_filters( 'plainleaf_llms_txt_content', rtrim( $out ) . "\n" );
}

/**
 * Answer /llms.txt before WordPress looks for a page, so there is no 404 and no
 * rewrite rule to flush.
 *
 * @param WP $wp The request.
 */
function plainleaf_llms_txt_serve( $wp ) {
	if ( 'llms.txt' !== $wp->request || ! apply_filters( 'plainleaf_llms_txt_enabled', true ) ) {
		return;
	}
	do_action( 'litespeed_control_set_nocache', 'llms.txt is built on each request' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- LiteSpeed Cache's own hook.
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Cache-Control: public, max-age=3600' );
	echo plainleaf_llms_txt_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plain text, cleaned per line.
	exit;
}
add_action( 'parse_request', 'plainleaf_llms_txt_serve', 0 );
