<?php
/**
 * Author photo block, front end.
 *
 * @package Plainleaf
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plainleaf_size  = isset( $attributes['size'] ) ? max( 24, min( 320, (int) $attributes['size'] ) ) : 56;
$plainleaf_photo = plainleaf_author_photo_html( $plainleaf_size );

// No photo chosen in Appearance > Theme settings: render nothing.
if ( '' === $plainleaf_photo ) {
	return;
}

printf(
	'<div %1$s style="--pl-author-size:%2$dpx">%3$s</div>',
	get_block_wrapper_attributes( array( 'class' => 'pl-author-photo' ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core.
	(int) $plainleaf_size,
	$plainleaf_photo // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with escaping.
);
