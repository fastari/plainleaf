<?php
/**
 * Home hero image or video (Appearance > Theme settings > Home hero).
 *
 * The media sits behind the hero text, either on the right half (fading into
 * the band) or across the whole band with a brand-colored tint so the text
 * keeps its contrast. A video is muted, loops, has a pause button (WCAG 2.2.2)
 * and never plays for visitors who ask for reduced motion.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The hero media markup, or an empty string when none is set.
 *
 * @return string
 */
function plainleaf_hero_media_html() {
	$media = plainleaf_hero_media();
	if ( ! $media ) {
		return '';
	}
	$class = 'pl-hero-art__media pl-hero-art__media--' . $media['layout'];

	if ( 'image' === $media['kind'] ) {
		$inner = wp_get_attachment_image(
			$media['id'],
			'full',
			false,
			array(
				'class'         => 'pl-hero-art__img',
				'alt'           => '',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
				'sizes'         => 'full' === $media['layout'] ? '100vw' : '(max-width: 781px) 100vw, 50vw',
			)
		);
		return '<div class="' . esc_attr( $class ) . '" aria-hidden="true">' . $inner . '</div>';
	}

	$url  = wp_get_attachment_url( $media['id'] );
	$mime = (string) get_post_mime_type( $media['id'] );
	if ( ! $url ) {
		return '';
	}
	return sprintf(
		'<div class="%1$s" aria-hidden="true"><video class="pl-hero-art__video" muted loop playsinline preload="metadata" data-pl-hero-video><source src="%2$s" type="%3$s"></video></div>' .
		'<button type="button" class="pl-hero-art__pause" data-pl-hero-pause aria-pressed="false" aria-label="%4$s" data-label-pause="%4$s" data-label-play="%5$s" hidden>%6$s%7$s</button>',
		esc_attr( $class ),
		esc_url( $url ),
		esc_attr( $mime ),
		esc_attr__( 'Pause the background video', 'plainleaf' ),
		esc_attr__( 'Play the background video', 'plainleaf' ),
		plainleaf_icon( 'pause' ),
		plainleaf_icon( 'play' )
	);
}

/**
 * Play the hero video unless the visitor prefers reduced motion, and wire up
 * the pause button. Only printed on the front page when a video is set.
 */
function plainleaf_hero_video_script() {
	$media = plainleaf_hero_media();
	if ( ! $media || 'video' !== $media['kind'] || ! is_front_page() ) {
		return;
	}
	?>
	<script>
	( function () {
		var video = document.querySelector( '[data-pl-hero-video]' );
		var button = document.querySelector( '[data-pl-hero-pause]' );
		if ( ! video || ! button || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}
		function show( playing ) {
			button.setAttribute( 'aria-pressed', playing ? 'false' : 'true' );
			button.setAttribute( 'aria-label', playing ? button.dataset.labelPause : button.dataset.labelPlay );
			button.classList.toggle( 'is-paused', ! playing );
		}
		button.hidden = false;
		button.addEventListener( 'click', function () {
			if ( video.paused ) {
				video.play();
				show( true );
			} else {
				video.pause();
				show( false );
			}
		} );
		var started = video.play();
		if ( started && started.catch ) {
			started.catch( function () {
				show( false );
			} );
		}
		show( true );
	} )();
	</script>
	<?php
}
add_action( 'wp_footer', 'plainleaf_hero_video_script' );
