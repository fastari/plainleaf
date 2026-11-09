<?php
/**
 * Title: Page not found
 * Slug: plainleaf/not-found
 * Categories: plainleaf
 * Description: The 404 message, search and two buttons, from Appearance > Site text.
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<?php if ( '' !== plainleaf_text( 'nf_label' ) ) : ?>
<!-- wp:paragraph {"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
<p class="pl-label has-sans-font-family has-x-small-font-size"><?php echo esc_html( plainleaf_text( 'nf_label' ) ); ?></p>
<!-- /wp:paragraph -->
<?php endif; ?>
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php echo esc_html( plainleaf_text( 'nf_title' ) ); ?></h1>
<!-- /wp:heading -->
<?php if ( '' !== plainleaf_text( 'nf_text' ) ) : ?>
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php echo plainleaf_text_lines( 'nf_text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per line. ?></p>
<!-- /wp:paragraph -->
<?php endif; ?>
<?php echo plainleaf_search_block(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup with encoded attributes. ?>
<!-- wp:buttons -->
<div class="wp-block-buttons">
	<!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/start-here/' ) ); ?>"><?php echo esc_html( plainleaf_text( 'nf_start' ) ); ?></a></div>
	<!-- /wp:button -->
	<!-- wp:button {"className":"is-style-outline"} -->
	<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( plainleaf_blog_url() ); ?>"><?php echo esc_html( plainleaf_text( 'nf_all' ) ); ?></a></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
