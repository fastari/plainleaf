<?php
/**
 * Title: Newsletter signup
 * Slug: plainleaf/newsletter-signup
 * Categories: plainleaf, call-to-action
 * Description: The newsletter promise and signup form in a soft surface box. The form is connected once in Appearance > Theme settings > Newsletter.
 * Keywords: newsletter, signup, subscribe, email
 * Viewport Width: 1200
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Newsletter signup', 'plainleaf' ); ?>"},"className":"pl-signup-box","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"var(--wp--custom--radius--card)"}},"backgroundColor":"surface","layout":{"type":"constrained","contentSize":"36rem","justifyContent":"left"}} -->
<div class="wp-block-group pl-signup-box has-surface-background-color has-background" style="border-radius:var(--wp--custom--radius--card);padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
	<?php if ( '' !== plainleaf_text( 'signup_heading' ) ) : ?>
	<!-- wp:heading -->
	<h2 class="wp-block-heading"><?php echo esc_html( plainleaf_text( 'signup_heading' ) ); ?></h2>
	<!-- /wp:heading -->
	<?php endif; ?>

	<?php if ( '' !== plainleaf_text( 'signup_text' ) ) : ?>
	<!-- wp:paragraph -->
	<p><?php echo plainleaf_text_lines( 'signup_text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per line. ?></p>
	<!-- /wp:paragraph -->
	<?php endif; ?>

	<!-- wp:plainleaf/newsletter-form {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} /-->
</div>
<!-- /wp:group -->
