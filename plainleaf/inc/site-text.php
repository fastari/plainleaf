<?php
/**
 * Appearance > Site text.
 *
 * Every sentence, heading and link label visitors read is stored as an option and
 * edited on one admin page, so wording changes never need a theme release or a
 * Site Editor copy of a template. The theme only ships the starting text (the
 * defaults below).
 *
 * Topic names and descriptions come from Posts > Categories. Menu labels and
 * screen-reader-only labels are not here.
 *
 * Each field is saved as the option plainleaf_text_{key} and is also exposed over the
 * REST API (/wp/v2/settings).
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The editable text, grouped as it appears on the admin page.
 *
 * Field: label, default, type (text|textarea), required (an empty value falls
 * back to the default instead of hiding the text), help.
 *
 * @return array<string,array{title:string,intro:string,fields:array<string,array>}>
 */
function plainleaf_text_sections() {
	static $sections = null;
	if ( null !== $sections ) {
		return $sections;
	}
	$empty_hides = __( 'Leave empty to hide it.', 'plainleaf' );

	$sections = array(
		'menu'    => array(
			'title'  => __( 'Menu', 'plainleaf' ),
			'intro'  => __( 'The links at the top of every page, repeated in the footer.', 'plainleaf' ),
			'fields' => array(
				'nav_home'       => array( __( 'Home', 'plainleaf' ), __( 'Home', 'plainleaf' ), 'text', true, '' ),
				'nav_start'      => array( __( 'Start here', 'plainleaf' ), __( 'Start here', 'plainleaf' ), 'text', true, '' ),
				'nav_blog'       => array( __( 'Blog', 'plainleaf' ), __( 'Blog', 'plainleaf' ), 'text', true, '' ),
				'nav_about'      => array( __( 'About', 'plainleaf' ), __( 'About', 'plainleaf' ), 'text', true, '' ),
				'nav_newsletter' => array( __( 'Newsletter', 'plainleaf' ), __( 'Newsletter', 'plainleaf' ), 'text', true, '' ),
			),
		),
		'home'    => array(
			'title'  => __( 'Home page', 'plainleaf' ),
			'intro'  => __( 'The band at the top of the home page and the sections under it.', 'plainleaf' ),
			'fields' => array(
				'hero_title'      => array( __( 'Headline', 'plainleaf' ), __( 'Notes, stories and ideas worth keeping.', 'plainleaf' ), 'textarea', true, __( 'Each line shows on its own line.', 'plainleaf' ) ),
				'hero_subtitle'   => array( __( 'Line under the headline', 'plainleaf' ), __( 'New writing, published here first and sent by email.', 'plainleaf' ), 'textarea', false, $empty_hides ),
				'hero_promise'    => array( __( 'Note under the email box', 'plainleaf' ), __( 'One email per new post. Unsubscribe any time.', 'plainleaf' ), 'text', false, $empty_hides ),
				'home_empty'      => array( __( 'Before the first post', 'plainleaf' ), __( 'New writing is on its way. Join the newsletter and the first story will come to you.', 'plainleaf' ), 'textarea', false, __( 'Shown where the newest stories go until you publish one.', 'plainleaf' ) ),
				'read_more'       => array( __( 'Link under each featured story', 'plainleaf' ), __( 'Read the story', 'plainleaf' ), 'text', true, '' ),
				'earlier_heading' => array( __( 'Earlier stories heading', 'plainleaf' ), __( 'Earlier stories', 'plainleaf' ), 'text', false, $empty_hides ),
				'all_posts'       => array( __( 'Link to the blog', 'plainleaf' ), __( 'All posts', 'plainleaf' ), 'text', true, '' ),
				'topics_heading' => array( __( 'Topic links heading', 'plainleaf' ), __( 'Browse by topic', 'plainleaf' ), 'text', false, $empty_hides ),
			),
		),
		'author'  => array(
			'title'  => __( 'Author', 'plainleaf' ),
			'intro'  => __( 'The byline on the home page reads "Written by [name], [description]." The same line, with a link to About, ends every post. The photo is under Theme settings.', 'plainleaf' ),
			'fields' => array(
				'author_name'  => array( __( 'Name', 'plainleaf' ), '', 'text', false, __( 'Links to your About page. Leave empty to hide the byline.', 'plainleaf' ) ),
				'author_bio'   => array( __( 'Short description', 'plainleaf' ), '', 'text', false, __( 'Starts with a small letter, because it follows your name. Leave empty for just "Written by [name]."', 'plainleaf' ) ),
				'author_more'  => array( __( 'Link to About, at the end of posts', 'plainleaf' ), __( 'More about me', 'plainleaf' ), 'text', true, '' ),
			),
		),
		'signup'  => array(
			'title'  => __( 'Newsletter box and signup form', 'plainleaf' ),
			'intro'  => __( 'The box near the bottom of most pages, and the messages the email form shows. The button text is under Theme settings > Newsletter.', 'plainleaf' ),
			'fields' => array(
				'signup_heading'    => array( __( 'Box heading', 'plainleaf' ), __( 'Join the newsletter', 'plainleaf' ), 'text', false, $empty_hides ),
				'signup_text'       => array( __( 'Box text', 'plainleaf' ), __( 'Get each new post by email. Hit reply any time: it goes to a real inbox.', 'plainleaf' ), 'textarea', false, $empty_hides ),
				'email_placeholder' => array( __( 'Hint inside the email box', 'plainleaf' ), __( 'you@example.com', 'plainleaf' ), 'text', true, '' ),
				'signup_sending'    => array( __( 'While sending', 'plainleaf' ), __( 'Sending…', 'plainleaf' ), 'text', true, '' ),
				'signup_invalid'    => array( __( 'When the email address looks wrong', 'plainleaf' ), __( 'That email address doesn\'t look right. Please check it and try again.', 'plainleaf' ), 'text', true, '' ),
				'signup_pending'    => array( __( 'Before the newsletter is connected', 'plainleaf' ), __( 'The newsletter is almost ready. Check back soon.', 'plainleaf' ), 'text', true, '' ),
			),
		),
		'blog'    => array(
			'title'  => __( 'Blog, topic and search pages', 'plainleaf' ),
			'intro'  => __( 'Topic names and descriptions are edited under Posts > Categories.', 'plainleaf' ),
			'fields' => array(
				'blog_title'         => array( __( 'Blog heading', 'plainleaf' ), __( 'Blog', 'plainleaf' ), 'text', true, '' ),
				'blog_intro'         => array( __( 'Blog intro', 'plainleaf' ), __( 'Every story, newest first. Pick a topic to see just those.', 'plainleaf' ), 'textarea', false, $empty_hides ),
				'filter_all'         => array( __( 'First filter link (all posts)', 'plainleaf' ), __( 'All', 'plainleaf' ), 'text', true, '' ),
				'topic_label'       => array( __( 'Small label above a topic name', 'plainleaf' ), __( 'Topic', 'plainleaf' ), 'text', false, $empty_hides ),
				'list_empty'         => array( __( 'When a list has no posts', 'plainleaf' ), __( 'Nothing here yet. New writing is on its way.', 'plainleaf' ), 'text', true, '' ),
				'newer_posts'        => array( __( 'Link to newer posts', 'plainleaf' ), __( 'Newer posts', 'plainleaf' ), 'text', true, '' ),
				'older_posts'        => array( __( 'Link to older posts', 'plainleaf' ), __( 'Older posts', 'plainleaf' ), 'text', true, '' ),
				'search_placeholder' => array( __( 'Hint inside the search box', 'plainleaf' ), __( 'Search the stories', 'plainleaf' ), 'text', true, '' ),
				'search_button'      => array( __( 'Search button', 'plainleaf' ), __( 'Search', 'plainleaf' ), 'text', true, '' ),
			),
		),
		'posts'   => array(
			'title'  => __( 'Posts and Start here', 'plainleaf' ),
			'intro'  => '',
			'fields' => array(
				'related_heading' => array( __( 'Heading above related posts', 'plainleaf' ), __( 'Keep reading', 'plainleaf' ), 'text', false, $empty_hides ),
				'start_empty'     => array( __( 'Start here, before any post is tagged start-here', 'plainleaf' ), __( 'The first stories are on their way. Join the newsletter and they will come to you.', 'plainleaf' ), 'textarea', false, $empty_hides ),
			),
		),
		'listen'  => array(
			'title'  => __( 'Listen and share buttons', 'plainleaf' ),
			'intro'  => __( 'The round play and share buttons next to a post\'s date. These words are read out by screen readers and shown in the player bar; the buttons themselves are icons. Add audio to a post in the post editor sidebar under Listen.', 'plainleaf' ),
			'fields' => array(
				'listen_play'   => array( __( 'Play button', 'plainleaf' ), __( 'Listen', 'plainleaf' ), 'text', true, '' ),
				'listen_pause'  => array( __( 'Pause button', 'plainleaf' ), __( 'Pause', 'plainleaf' ), 'text', true, '' ),
				'listen_player' => array( __( 'Player bar name', 'plainleaf' ), __( 'Audio player', 'plainleaf' ), 'text', true, '' ),
				'listen_seek'   => array( __( 'Progress slider', 'plainleaf' ), __( 'Playback position', 'plainleaf' ), 'text', true, '' ),
				'listen_speed'  => array( __( 'Speed button', 'plainleaf' ), __( 'Playback speed', 'plainleaf' ), 'text', true, '' ),
				'listen_close'  => array( __( 'Close player', 'plainleaf' ), __( 'Close player', 'plainleaf' ), 'text', true, '' ),
				'share_label'   => array( __( 'Share button', 'plainleaf' ), __( 'Share', 'plainleaf' ), 'text', true, '' ),
				'share_email'   => array( __( 'Share by email', 'plainleaf' ), __( 'Email', 'plainleaf' ), 'text', true, '' ),
				'share_copy'    => array( __( 'Copy link', 'plainleaf' ), __( 'Copy link', 'plainleaf' ), 'text', true, '' ),
				'share_copied'  => array( __( 'After copying', 'plainleaf' ), __( 'Link copied', 'plainleaf' ), 'text', true, '' ),
			),
		),
		'footer'  => array(
			'title'  => __( 'Footer', 'plainleaf' ),
			'intro'  => '',
			'fields' => array(
				'footer_tagline'    => array( __( 'One-liner under the name', 'plainleaf' ), '', 'textarea', false, $empty_hides ),
				'footer_note_title' => array( __( 'Footer note: bold start', 'plainleaf' ), '', 'text', false, '' ),
				'footer_privacy'    => array( __( 'Privacy link', 'plainleaf' ), __( 'Privacy', 'plainleaf' ), 'text', true, __( 'Shows once a privacy page is published (Settings > Privacy). The copyright uses the Site Title from Settings > General.', 'plainleaf' ) ),
				'footer_note'       => array( __( 'Footer note', 'plainleaf' ), '', 'textarea', false, __( 'The small grey note under the one-liner. Leave empty to hide it.', 'plainleaf' ) ),
			),
		),
		'ai'       => array(
			'title'  => __( 'AI tools (llms.txt)', 'plainleaf' ),
			'intro'  => plainleaf_llms_txt_file_exists()
				? __( 'Another plugin has put an llms.txt file in the site folder, so AI tools get that file instead of this one. Turn off LLMs.txt in Hostinger Tools (or the plugin that made it) to use this version.', 'plainleaf' )
				: __( 'The guide to this site that AI tools read at /llms.txt. It lists your pages, topics and posts with their excerpts, skipping anything hidden from search engines, and updates by itself.', 'plainleaf' ),
			'fields' => array(
				'llms_summary' => array( __( 'Summary under the tagline', 'plainleaf' ), '', 'textarea', false, __( 'Leave empty to use the line under the homepage headline.', 'plainleaf' ) ),
				'llms_pages'   => array( __( 'Pages heading', 'plainleaf' ), __( 'Pages', 'plainleaf' ), 'text', true, '' ),
				'llms_topics' => array( __( 'Topics heading', 'plainleaf' ), __( 'Topics', 'plainleaf' ), 'text', true, '' ),
				'llms_posts'   => array( __( 'Posts heading', 'plainleaf' ), __( 'Posts', 'plainleaf' ), 'text', true, '' ),
			),
		),
		'notfound' => array(
			'title'  => __( 'Page not found', 'plainleaf' ),
			'intro'  => __( 'Shown when a link points to a page that doesn\'t exist.', 'plainleaf' ),
			'fields' => array(
				'nf_label' => array( __( 'Small label', 'plainleaf' ), __( 'Page not found', 'plainleaf' ), 'text', false, $empty_hides ),
				'nf_title' => array( __( 'Heading', 'plainleaf' ), __( 'This page isn\'t here.', 'plainleaf' ), 'text', true, '' ),
				'nf_text'  => array( __( 'Text', 'plainleaf' ), __( 'It may have moved, or the link may have a typo. Try a search, or start from one of these.', 'plainleaf' ), 'textarea', false, $empty_hides ),
				'nf_start' => array( __( 'First button (to Start here)', 'plainleaf' ), __( 'Start here', 'plainleaf' ), 'text', true, '' ),
				'nf_all'   => array( __( 'Second button (to the blog)', 'plainleaf' ), __( 'All stories', 'plainleaf' ), 'text', true, '' ),
			),
		),
	);
	return $sections;
}

/**
 * One field's definition, or null.
 *
 * @param string $key Field key.
 * @return array|null
 */
function plainleaf_text_field( $key ) {
	foreach ( plainleaf_text_sections() as $section ) {
		if ( isset( $section['fields'][ $key ] ) ) {
			return $section['fields'][ $key ];
		}
	}
	return null;
}

/**
 * The text to show for a field: the saved value, or the theme's starting text
 * when it has never been saved. A field saved empty is hidden, unless it is
 * required, in which case the starting text is used.
 *
 * @param string $key Field key, for example hero_title.
 * @return string Plain text (not escaped).
 */
function plainleaf_text( $key ) {
	$field = plainleaf_text_field( $key );
	if ( ! $field ) {
		return '';
	}
	$value = get_option( 'plainleaf_text_' . $key, null );
	if ( null === $value || false === $value ) {
		return $field[1];
	}
	$value = trim( (string) $value );
	if ( '' === $value && $field[3] ) {
		return $field[1];
	}
	return $value;
}

/**
 * Escaped text with line breaks kept, for multi-line fields such as the headline.
 *
 * @param string $key Field key.
 * @return string HTML.
 */
function plainleaf_text_lines( $key ) {
	$lines = preg_split( '/\r\n|\r|\n/', plainleaf_text( $key ) );
	$lines = array_filter( array_map( 'trim', $lines ), 'strlen' );
	return implode( '<br>', array_map( 'esc_html', $lines ) );
}

/**
 * "Written by [name], [description]." with the name linking to About.
 *
 * @param bool $with_more Add the "More about me" link after it (end of posts).
 * @return string HTML.
 */
function plainleaf_byline_html( $with_more = false ) {
	$name = '<a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html( plainleaf_text( 'author_name' ) ) . '</a>';
	$bio  = plainleaf_text( 'author_bio' );
	if ( '' !== $bio ) {
		/* translators: 1: author name linking to About, 2: short description such as "a builder and former startup CTO". */
		$html = sprintf( esc_html__( 'Written by %1$s, %2$s.', 'plainleaf' ), $name, esc_html( rtrim( $bio, '.' ) ) );
	} else {
		/* translators: %s: author name linking to About. */
		$html = sprintf( esc_html__( 'Written by %s.', 'plainleaf' ), $name );
	}
	if ( $with_more ) {
		$html .= ' <a href="' . esc_url( home_url( '/about/' ) ) . '">' . esc_html( plainleaf_text( 'author_more' ) ) . '</a>';
	}
	return $html;
}

/**
 * A text field as a JSON string for a block comment attribute, quotes included.
 * Uses the same encoding WordPress uses when it saves blocks, so quotes, dashes
 * and angle brackets typed into a field can't break the block.
 *
 * @param string $key Field key.
 * @return string For example "Read the story" (with the quotes).
 */
function plainleaf_text_attr( $key ) {
	return substr( serialize_block_attributes( array( 'v' => plainleaf_text( $key ) ) ), 5, -1 );
}

/**
 * Search block markup with the editable hint and button text.
 *
 * @return string Block markup.
 */
function plainleaf_search_block() {
	return '<!-- wp:search ' . serialize_block_attributes(
		array(
			'label'       => __( 'Search', 'plainleaf' ),
			'showLabel'   => false,
			'placeholder' => plainleaf_text( 'search_placeholder' ),
			'buttonText'  => plainleaf_text( 'search_button' ),
		)
	) . ' /-->';
}

/**
 * The topics: every category except the catch-all one (Uncategorized), in the
 * site's reading order, with names and descriptions from Posts > Categories.
 *
 * The default category is left out only when it isn't one of the topics, so
 * choosing a topic as the default category (Settings > Writing) never hides it.
 *
 * @return array<string,array{name:string,description:string,url:string}>
 */
function plainleaf_topics() {
	$order   = array( 'finding-your-voice', 'leading-by-listening', 'building-together', 'from-the-workbench', 'build-notes' );
	$default = (int) get_option( 'default_category' );
	$terms   = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
		)
	);
	$topics = array();
	if ( ! is_wp_error( $terms ) ) {
		$terms = array_values(
			array_filter(
				$terms,
				function ( $term ) use ( $order, $default ) {
					if ( 'uncategorized' === $term->slug ) {
						return false;
					}
					return ! ( (int) $term->term_id === $default && ! in_array( $term->slug, $order, true ) );
				}
			)
		);
		usort(
			$terms,
			function ( $a, $b ) use ( $order ) {
				$ia = array_search( $a->slug, $order, true );
				$ib = array_search( $b->slug, $order, true );
				$ia = false === $ia ? 99 : $ia;
				$ib = false === $ib ? 99 : $ib;
				return $ia === $ib ? strcasecmp( $a->name, $b->name ) : $ia - $ib;
			}
		);
		foreach ( $terms as $term ) {
			$topics[ $term->slug ] = array(
				'name'        => $term->name,
				'description' => wp_strip_all_tags( $term->description ),
				'url'         => get_category_link( $term ),
			);
		}
	}
	return $topics;
}

/**
 * Register one option per field.
 */
function plainleaf_text_register_settings() {
	foreach ( plainleaf_text_sections() as $section ) {
		foreach ( $section['fields'] as $key => $field ) {
			register_setting(
				'plainleaf_text',
				'plainleaf_text_' . $key,
				array(
					'type'              => 'string',
					'description'       => $field[0],
					'sanitize_callback' => 'textarea' === $field[2] ? 'sanitize_textarea_field' : 'sanitize_text_field',
					'show_in_rest'      => true,
				)
			);
		}
	}
}
add_action( 'init', 'plainleaf_text_register_settings' );

/**
 * Add Appearance > Site text.
 */
function plainleaf_text_menu() {
	add_theme_page(
		__( 'Site text', 'plainleaf' ),
		__( 'Site text', 'plainleaf' ),
		'manage_options',
		'pl-site-text',
		'plainleaf_text_page'
	);
}
add_action( 'admin_menu', 'plainleaf_text_menu' );

/**
 * Sections and fields for the admin page.
 */
function plainleaf_text_admin_fields() {
	foreach ( plainleaf_text_sections() as $id => $section ) {
		add_settings_section(
			'plainleaf_text_' . $id,
			$section['title'],
			function () use ( $section ) {
				if ( '' !== $section['intro'] ) {
					echo '<p>' . esc_html( $section['intro'] ) . '</p>';
				}
			},
			'pl-site-text'
		);
		foreach ( $section['fields'] as $key => $field ) {
			add_settings_field(
				'plainleaf_text_' . $key,
				$field[0],
				'plainleaf_text_render_field',
				'pl-site-text',
				'plainleaf_text_' . $id,
				array(
					'label_for' => 'plainleaf_text_' . $key,
					'key'       => $key,
					'field'     => $field,
				)
			);
		}
	}
}
add_action( 'admin_init', 'plainleaf_text_admin_fields' );

/**
 * One input or textarea, showing the text currently on the site.
 *
 * @param array $args Field arguments.
 */
function plainleaf_text_render_field( $args ) {
	$name  = $args['label_for'];
	$field = $args['field'];
	$saved = get_option( $name, null );
	$value = ( null === $saved || false === $saved ) ? $field[1] : (string) $saved;

	if ( 'textarea' === $field[2] ) {
		$rows = max( 2, min( 5, substr_count( $field[1], "\n" ) + 1 + (int) ( strlen( $field[1] ) / 90 ) ) );
		printf( '<textarea class="large-text" rows="%3$d" id="%1$s" name="%1$s">%2$s</textarea>', esc_attr( $name ), esc_textarea( $value ), (int) $rows );
	} else {
		printf( '<input type="text" class="large-text" id="%1$s" name="%1$s" value="%2$s">', esc_attr( $name ), esc_attr( $value ) );
	}
	$help = $field[4];
	if ( $field[3] ) {
		$help = trim( $help . ' ' . __( 'If left empty, the theme\'s text is used.', 'plainleaf' ) );
	}
	if ( '' !== $help ) {
		echo '<p class="description">' . esc_html( $help ) . '</p>';
	}
	if ( $value !== $field[1] ) {
		/* translators: %s: the text that ships with the theme. */
		echo '<p class="description">' . esc_html( sprintf( __( 'Theme\'s original: %s', 'plainleaf' ), str_replace( "\n", ' / ', $field[1] ) ) ) . '</p>';
	}
}

/**
 * Page markup.
 */
function plainleaf_text_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'Site text', 'plainleaf' ) . '</h1>';
	echo '<p>' . esc_html__( 'Everything visitors read that isn\'t a page or a post. Edit any line, save, and clear the site cache to see it. Page and post content is edited in the editor as usual.', 'plainleaf' ) . '</p>';
	settings_errors();
	echo '<form action="options.php" method="post">';
	settings_fields( 'plainleaf_text' );
	do_settings_sections( 'pl-site-text' );
	submit_button();
	echo '</form></div>';
}

/**
 * Whether a published page exists at this address, so menus never link to a
 * page that isn't there (Start here, About, Newsletter).
 *
 * @param string $slug Page path, such as "about".
 * @return bool
 */
function plainleaf_page_exists( $slug ) {
	static $cache = array();
	if ( ! isset( $cache[ $slug ] ) ) {
		$page           = get_page_by_path( $slug );
		$cache[ $slug ] = $page instanceof WP_Post && 'publish' === $page->post_status;
	}
	return (bool) apply_filters( 'plainleaf_page_exists', $cache[ $slug ], $slug );
}
