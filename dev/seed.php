<?php
/**
 * Local development seed. NOT part of the theme zip.
 *
 * Creates the site structure the theme expects so templates can be checked:
 * demo topics (categories), the start-here tag, the main pages, reading settings
 * and a few short demo posts.
 *
 * Safe to run more than once: it only creates what is missing.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	require_once '/wordpress/wp-load.php';
}

/**
 * A paragraph block.
 *
 * @param string $text Paragraph text.
 * @return string
 */
function plainleaf_seed_paragraph( $text ) {
	return "<!-- wp:paragraph -->\n<p>" . $text . "</p>\n<!-- /wp:paragraph -->\n\n";
}

/**
 * Create a page if no page with that slug exists.
 *
 * @param string $slug    Page slug.
 * @param string $title   Page title.
 * @param string $content Page content.
 * @return int Page ID.
 */
function plainleaf_seed_page( $slug, $title, $content = '' ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return (int) $existing->ID;
	}
	return (int) wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => $content,
		)
	);
}

/**
 * Seed the demo site.
 */
function plainleaf_dev_seed() {
	global $wp_rewrite;

	update_option( 'blogname', 'Plainleaf Demo' );
	update_option( 'blogdescription', 'Notes, stories and ideas worth keeping.' );
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	update_option( 'posts_per_page', 10 );

	$topics  = array(
		'notes'    => array( 'Notes', 'Short thoughts and things learned along the way.' ),
		'essays'   => array( 'Essays', 'Longer pieces that take an idea and follow it all the way through.' ),
		'projects' => array( 'Projects', 'Things being built, with the decisions and mistakes behind them.' ),
		'reading'  => array( 'Reading', 'Books and articles worth your time, and what to take from them.' ),
	);
	$cat_ids = array();
	foreach ( $topics as $slug => $topic ) {
		$existing = get_term_by( 'slug', $slug, 'category' );
		if ( $existing ) {
			$cat_ids[ $slug ] = (int) $existing->term_id;
			continue;
		}
		$created          = wp_insert_term(
			$topic[0],
			'category',
			array(
				'slug'        => $slug,
				'description' => $topic[1],
			)
		);
		$cat_ids[ $slug ] = is_wp_error( $created ) ? 0 : (int) $created['term_id'];
	}
	if ( ! term_exists( 'start-here', 'post_tag' ) ) {
		wp_insert_term( 'Start here', 'post_tag', array( 'slug' => 'start-here' ) );
	}

	$home = plainleaf_seed_page( 'home', 'Home' );
	$blog = plainleaf_seed_page( 'blog', 'Blog' );
	plainleaf_seed_page( 'start-here', 'Start here', plainleaf_seed_paragraph( 'New here? These are the posts to read first, in order.' ) );
	// About uses the theme's starter pattern, the same one site owners pick for a new page.
	$about_pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'plainleaf/about-starter' );
	plainleaf_seed_page( 'about', 'About', $about_pattern ? $about_pattern['content'] : '' );
	plainleaf_seed_page(
		'newsletter',
		'Newsletter',
		plainleaf_seed_paragraph( 'Get each new post by email. Hit reply any time: it goes to a real inbox.' ) . '<!-- wp:plainleaf/newsletter-form /-->'
	);

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home );
	update_option( 'page_for_posts', $blog );

	// Remove the default "Hello world!" post and "Sample Page" if still there.
	$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $hello && 'Hello world!' === $hello->post_title ) {
		wp_delete_post( $hello->ID, true );
	}
	$sample = get_page_by_path( 'sample-page' );
	if ( $sample ) {
		wp_delete_post( $sample->ID, true );
	}
	$uncategorized = get_term_by( 'slug', 'uncategorized', 'category' );
	if ( $uncategorized ) {
		update_option( 'default_category', $cat_ids['notes'] );
		wp_delete_term( $uncategorized->term_id, 'category' );
	}

	// Demo posts so the lists have something to show.
	$demo_posts = array(
		array( 'Why I Keep a Plain Notebook', 'notes', 'Paper has no notifications. A short case for writing things down before writing them up.', '-1 days', true ),
		array( 'The First Draft Is for You', 'essays', 'Nobody reads the first draft but you, so let it be messy. Editing is where the reader comes in.', '-8 days', true ),
		array( 'Building a Bookshelf, Badly', 'projects', 'What a crooked shelf taught me about planning, measuring twice, and asking for help sooner.', '-15 days', false ),
		array( 'Three Books on Paying Attention', 'reading', 'Short notes on three books about focus, and the one idea from each that stuck.', '-22 days', true ),
	);
	foreach ( $demo_posts as $demo ) {
		if ( get_page_by_title( $demo[0], OBJECT, 'post' ) ) { // phpcs:ignore WordPress.WP.DeprecatedFunctions -- dev only.
			continue;
		}
		$body   = str_repeat( plainleaf_seed_paragraph( $demo[2] . ' This demo paragraph repeats so the reading time and the layout have something to work with. Replace it with your own writing.' ), 12 );
		$new_id = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $demo[0],
				'post_excerpt'  => $demo[2],
				'post_content'  => $body,
				'post_date'     => gmdate( 'Y-m-d H:i:s', strtotime( $demo[3] ) ),
				'post_category' => array( $cat_ids[ $demo[1] ] ),
			)
		);
		if ( $demo[4] && $new_id ) {
			wp_set_post_tags( $new_id, array( 'start-here' ), true );
		}
	}

	// Demo author: a fictional name and the example portrait that ships with the theme.
	if ( ! get_option( 'plainleaf_author_photo' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$tmp = wp_tempnam( 'demo-portrait.webp' );
		copy( get_template_directory() . '/assets/images/demo-portrait.webp', $tmp );
		$photo = media_handle_sideload(
			array(
				'name'     => 'demo-portrait.webp',
				'tmp_name' => $tmp,
			),
			0
		);
		if ( ! is_wp_error( $photo ) ) {
			update_post_meta( $photo, '_wp_attachment_image_alt', 'Photo of Sam Rivera' );
			update_option( 'plainleaf_author_photo', $photo );
		}
	}
	if ( false === get_option( 'plainleaf_text_author_name' ) ) {
		update_option( 'plainleaf_text_author_name', 'Sam Rivera' );
		update_option( 'plainleaf_text_author_bio', 'a writer who keeps a plain notebook' );
	}

	flush_rewrite_rules( false );
}

plainleaf_dev_seed();
echo "Seeded.\n";
