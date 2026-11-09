<?php
/**
 * Listen and share.
 *
 * Two small round buttons at the end of a post's date row:
 *   - Play: only when the post has an audio file (post editor sidebar > Listen).
 *     It plays in a slim bar at the bottom of the screen, keeps playing with the
 *     phone locked, and shows the post title on the lock screen.
 *   - Share: the phone's own share sheet on phones; on computers a small menu
 *     with LinkedIn, X, Email and Copy link. Plain links, no third-party scripts.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The audio file for a post, stored as a media library ID.
 */
function plainleaf_listen_register_meta() {
	register_post_meta(
		'post',
		'plainleaf_audio_id',
		array(
			'type'              => 'integer',
			'description'       => __( 'Media library ID of the audio version of this post.', 'plainleaf' ),
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => function ( $allowed, $meta_key, $post_id ) {
				unset( $allowed, $meta_key );
				return current_user_can( 'edit_post', $post_id );
			},
		)
	);
}
add_action( 'init', 'plainleaf_listen_register_meta' );

/**
 * A post's audio: URL, MIME type and length in seconds, or null when there is none.
 *
 * @param int $post_id Post ID.
 * @return array{url:string,mime:string,length:int}|null
 */
function plainleaf_post_audio( $post_id ) {
	$id = (int) get_post_meta( (int) $post_id, 'plainleaf_audio_id', true );
	if ( ! $id || 'attachment' !== get_post_type( $id ) ) {
		return null;
	}
	$mime = (string) get_post_mime_type( $id );
	$url  = wp_get_attachment_url( $id );
	if ( 0 !== strpos( $mime, 'audio/' ) || ! $url ) {
		return null;
	}
	$meta = wp_get_attachment_metadata( $id );
	return array(
		'url'    => $url,
		'mime'   => $mime,
		'length' => isset( $meta['length'] ) ? (int) $meta['length'] : 0,
	);
}

/**
 * "Listen" panel in the post editor sidebar.
 */
function plainleaf_listen_editor_assets() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'post' !== $screen->base || 'post' !== $screen->post_type ) {
		return; // Only the post editor, not pages or the Site Editor.
	}
	wp_enqueue_script(
		'pl-listen-editor',
		get_theme_file_uri( 'assets/js/listen-editor.js' ),
		array( 'wp-plugins', 'wp-editor', 'wp-element', 'wp-components', 'wp-data', 'wp-core-data', 'wp-block-editor', 'wp-i18n' ),
		PLAINLEAF_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'plainleaf_listen_editor_assets' );

/**
 * Front-end script for the two buttons and the player bar. Enqueued by the
 * post-actions pattern only on posts.
 */
function plainleaf_post_actions_enqueue() {
	wp_enqueue_script(
		'pl-post-actions',
		get_theme_file_uri( 'assets/js/post-actions.js' ),
		array(),
		PLAINLEAF_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}

/**
 * Inline SVG icons (24px grid, drawn with currentColor).
 *
 * @param string $name play|pause|share|close|linkedin|x|mail|link.
 * @return string
 */
function plainleaf_icon( $name ) {
	$paths = array(
		'play'     => '<path d="M8 5.5v13l11-6.5z" fill="currentColor"/>',
		'pause'    => '<path d="M7 5h3.5v14H7zM13.5 5H17v14h-3.5z" fill="currentColor"/>',
		'share'    => '<path d="M12 15V3.5M7.5 8 12 3.5 16.5 8M5 12.5V19a1.5 1.5 0 0 0 1.5 1.5h11A1.5 1.5 0 0 0 19 19v-6.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
		'close'    => '<path d="M6 6l12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
		'linkedin' => '<path d="M5.5 9.5h3v9h-3zM7 4.75a1.75 1.75 0 1 1 0 3.5 1.75 1.75 0 0 1 0-3.5zM10.5 9.5h2.9v1.25c.45-.8 1.5-1.5 3.05-1.5 3 0 3.55 1.9 3.55 4.4v4.85h-3v-4.3c0-1.05-.02-2.35-1.45-2.35-1.45 0-1.67 1.12-1.67 2.28v4.37h-2.98z" fill="currentColor"/>',
		'x'        => '<path d="M5 4.5h4.2l3.3 4.6 3.9-4.6h1.9l-4.9 5.8 5.6 7.7h-4.2l-3.6-5-4.2 5H5.1l5.2-6.1z" fill="currentColor"/>',
		'mail'     => '<path d="M4 6.5h16v11H4zM4.5 7l7.5 6 7.5-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
		'link'     => '<path d="M10 14a4 4 0 0 0 5.66 0l3-3a4 4 0 0 0-5.66-5.66l-1 1M14 10a4 4 0 0 0-5.66 0l-3 3a4 4 0 0 0 5.66 5.66l1-1" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg class="pl-icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/**
 * The buttons' HTML for one post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function plainleaf_post_actions_html( $post_id ) {
	$post_id = (int) $post_id;
	if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
		return '';
	}
	plainleaf_post_actions_enqueue();

	$url   = get_permalink( $post_id );
	$title = wp_strip_all_tags( get_the_title( $post_id ) );
	$audio = plainleaf_post_audio( $post_id );

	$labels = array(
		'listen' => plainleaf_text( 'listen_play' ),
		'pause'  => plainleaf_text( 'listen_pause' ),
		'player' => plainleaf_text( 'listen_player' ),
		'close'  => plainleaf_text( 'listen_close' ),
		'speed'  => plainleaf_text( 'listen_speed' ),
		'seek'   => plainleaf_text( 'listen_seek' ),
		'copied' => plainleaf_text( 'share_copied' ),
	);
	$icons  = array(
		'play'  => plainleaf_icon( 'play' ),
		'pause' => plainleaf_icon( 'pause' ),
		'close' => plainleaf_icon( 'close' ),
	);

	$html = '<div class="pl-actions" data-pl-actions data-labels="' . esc_attr( wp_json_encode( $labels ) ) . '">';
	// The script clones these instead of turning attribute text into HTML.
	foreach ( $icons as $plainleaf_icon_name => $plainleaf_icon_svg ) {
		$html .= '<template data-pl-icon="' . esc_attr( $plainleaf_icon_name ) . '">' . $plainleaf_icon_svg . '</template>';
	}
	if ( $audio ) {
		$artwork = get_site_icon_url( 512 );
		$html   .= sprintf(
			'<button type="button" class="pl-action pl-action--listen" data-pl-listen data-src="%1$s" data-type="%2$s" data-title="%3$s" data-artist="%4$s" data-artwork="%5$s" aria-label="%6$s">%7$s</button>',
			esc_url( $audio['url'] ),
			esc_attr( $audio['mime'] ),
			esc_attr( $title ),
			esc_attr( '' !== plainleaf_text( 'author_name' ) ? plainleaf_text( 'author_name' ) : get_bloginfo( 'name' ) ),
			esc_url( $artwork ? $artwork : '' ),
			esc_attr( plainleaf_text( 'listen_play' ) ),
			plainleaf_icon( 'play' )
		);
	}
	$menu_id = 'pl-share-' . $post_id;
	$links   = array(
		'linkedin' => array( 'LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $url ) ),
		'x'        => array( 'X', 'https://x.com/intent/post?url=' . rawurlencode( $url ) . '&text=' . rawurlencode( $title ) ),
		'mail'     => array( plainleaf_text( 'share_email' ), 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( $url ) ),
	);
	$html .= sprintf(
		'<button type="button" class="pl-action" data-pl-share data-url="%1$s" data-title="%2$s" aria-label="%3$s" aria-expanded="false" aria-controls="%4$s">%5$s</button>',
		esc_url( $url ),
		esc_attr( $title ),
		esc_attr( plainleaf_text( 'share_label' ) ),
		esc_attr( $menu_id ),
		plainleaf_icon( 'share' )
	);
	$html .= '<div class="pl-share-menu" id="' . esc_attr( $menu_id ) . '" hidden><ul>';
	foreach ( $links as $icon => $link ) {
		$html .= sprintf(
			'<li><a href="%1$s"%2$s>%3$s<span>%4$s</span></a></li>',
			esc_url( $link[1], array( 'https', 'mailto' ) ),
			'mail' === $icon ? '' : ' target="_blank" rel="noopener noreferrer"',
			plainleaf_icon( $icon ),
			esc_html( $link[0] )
		);
	}
	$html .= sprintf(
		'<li><button type="button" data-pl-copy>%1$s<span>%2$s</span></button></li>',
		plainleaf_icon( 'link' ),
		esc_html( plainleaf_text( 'share_copy' ) )
	);
	$html .= '</ul></div></div>';
	return $html;
}
