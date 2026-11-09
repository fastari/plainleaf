<?php
/**
 * Logo block, front end.
 *
 * Shows the Site Logo set in WordPress (Site Editor, or Settings > General on
 * some hosts), the site title, or both, following Appearance > Theme settings >
 * Header shows. With a compact phone logo set there, phones see that instead.
 * Nothing here comes from theme files, so a theme update never changes the logo.
 *
 * @package Plainleaf
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plainleaf_size    = ( isset( $attributes['size'] ) && 'footer' === $attributes['size'] ) ? 'footer' : 'header';
$plainleaf_is_link = ! isset( $attributes['isLink'] ) || (bool) $attributes['isLink'];
$plainleaf_phone   = ( 'header' === $plainleaf_size ) && ( ! isset( $attributes['showIcon'] ) || (bool) $attributes['showIcon'] );
$plainleaf_logo_id = plainleaf_logo_id();
$plainleaf_mode    = get_option( 'plainleaf_logo_mode', 'auto' );
$plainleaf_name    = get_bloginfo( 'name' );
$plainleaf_loading = 'header' === $plainleaf_size ? array( 'fetchpriority' => 'high' ) : array( 'loading' => 'lazy' );

$plainleaf_show_logo  = $plainleaf_logo_id && 'title' !== $plainleaf_mode;
$plainleaf_show_title = ! $plainleaf_show_logo || 'both' === $plainleaf_mode;

$plainleaf_inner   = '';
$plainleaf_classes = array( 'pl-wordmark', 'pl-wordmark--' . $plainleaf_size );

if ( $plainleaf_show_logo ) {
	$plainleaf_inner .= wp_get_attachment_image(
		$plainleaf_logo_id,
		'full',
		false,
		array_merge(
			array(
				'class'    => 'pl-wordmark__text',
				// When the title is shown next to the logo, the logo is decorative.
				'alt'      => $plainleaf_show_title ? '' : $plainleaf_name,
				'decoding' => 'async',
			),
			$plainleaf_loading
		)
	);

	$plainleaf_phone_id = $plainleaf_phone ? (int) get_option( 'plainleaf_logo_phone', 0 ) : 0;
	if ( $plainleaf_phone_id && wp_attachment_is_image( $plainleaf_phone_id ) ) {
		$plainleaf_classes[] = 'pl-wordmark--has-icon';
		$plainleaf_inner    .= wp_get_attachment_image(
			$plainleaf_phone_id,
			'medium',
			false,
			array(
				'class'    => 'pl-wordmark__icon',
				'alt'      => $plainleaf_show_title ? '' : $plainleaf_name,
				'decoding' => 'async',
			)
		);
	}
}

if ( $plainleaf_show_title ) {
	$plainleaf_inner .= '<span class="pl-wordmark__title">' . esc_html( $plainleaf_name ) . '</span>';
}

$plainleaf_wrapper = get_block_wrapper_attributes( array( 'class' => implode( ' ', $plainleaf_classes ) ) );

if ( $plainleaf_is_link ) {
	printf(
		'<a %1$s href="%2$s" rel="home"%3$s>%4$s</a>',
		$plainleaf_wrapper, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes().
		esc_url( home_url( '/' ) ),
		( is_front_page() && ! is_paged() ) ? ' aria-current="page"' : '',
		$plainleaf_inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above.
	);
} else {
	printf(
		'<div %1$s>%2$s</div>',
		$plainleaf_wrapper, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes().
		$plainleaf_inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above.
	);
}
