<?php
/**
 * Title: Hero: home
 * Slug: plainleaf/hero-art
 * Categories: plainleaf, banner
 * Description: Brand-colored band with the headline, email signup and byline. Shows the image or video from Appearance > Theme settings > Home hero, and has an empty decorative layer behind the text that you can style with Additional CSS (class pl-hero-art__layer).
 * Keywords: hero, banner, intro, home
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Hero: home', 'plainleaf' ); ?>"},"align":"full","className":"pl-intro pl-hero-art","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|70"},"margin":{"top":"0","bottom":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|on-brand"}}}},"backgroundColor":"brand","textColor":"on-brand","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull pl-intro pl-hero-art has-on-brand-color has-brand-background-color has-text-color has-background has-link-color" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:html -->
	<?php echo plainleaf_hero_media_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in plainleaf_hero_media_html(). ?>
	<div class="pl-hero-art__layer" aria-hidden="true"><span></span><span></span><span></span></div>
	<!-- /wp:html -->

	<!-- wp:group {"align":"wide","className":"pl-hero-art__copy","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"40rem","justifyContent":"left"}} -->
	<div class="wp-block-group alignwide pl-hero-art__copy">
<?php require get_theme_file_path( 'inc/hero-copy.php' ); ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
