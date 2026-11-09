<?php
/**
 * Newsletter form block, front end.
 *
 * @package Plainleaf
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plainleaf_variant = ( isset( $attributes['variant'] ) && 'hero' === $attributes['variant'] ) ? 'hero' : 'default';

printf(
	'<div %1$s>%2$s</div>',
	get_block_wrapper_attributes( array( 'class' => 'pl-signup pl-signup--' . $plainleaf_variant ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by core.
	plainleaf_newsletter_form_html( array( 'variant' => $plainleaf_variant ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the builder.
);
