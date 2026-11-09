<?php
/**
 * Title: Blog heading
 * Slug: plainleaf/blog-header
 * Categories: plainleaf
 * Description: The Blog page heading and intro, from Appearance > Site text.
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php echo esc_html( plainleaf_text( 'blog_title' ) ); ?></h1>
<!-- /wp:heading -->
<?php if ( '' !== plainleaf_text( 'blog_intro' ) ) : ?>
<!-- wp:paragraph {"textColor":"muted","fontSize":"large"} -->
<p class="has-muted-color has-text-color has-large-font-size"><?php echo plainleaf_text_lines( 'blog_intro' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per line. ?></p>
<!-- /wp:paragraph -->
<?php endif; ?>
