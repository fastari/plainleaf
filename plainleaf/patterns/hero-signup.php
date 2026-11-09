<?php
/**
 * Title: Hero: with signup card
 * Slug: plainleaf/hero-signup
 * Categories: plainleaf, banner, call-to-action
 * Description: Brand-colored band with the promise on the left and a newsletter signup card on the right. Stacks on phones.
 * Keywords: hero, banner, intro, home, newsletter, signup
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Hero: with signup card', 'plainleaf' ); ?>"},"align":"full","className":"pl-intro","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"margin":{"top":"0","bottom":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|on-brand"}}}},"backgroundColor":"brand","textColor":"on-brand","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull pl-intro has-on-brand-color has-brand-background-color has-text-color has-background has-link-color" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|60","top":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"58%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
<?php
$plainleaf_hero_form = false;
require get_theme_file_path( 'inc/hero-copy.php' );
?>
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%">
			<!-- wp:group {"className":"pl-hero-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"var(--wp--custom--radius--card)"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}},"backgroundColor":"base","textColor":"contrast","layout":{"type":"default"}} -->
			<div class="wp-block-group pl-hero-card has-contrast-color has-base-background-color has-text-color has-background has-link-color" style="border-radius:var(--wp--custom--radius--card);padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:heading {"level":2,"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
				<h2 class="wp-block-heading pl-label has-sans-font-family has-x-small-font-size"><?php esc_html_e( 'The newsletter', 'plainleaf' ); ?></h2>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.25"}},"fontFamily":"display","fontSize":"x-large"} -->
				<p class="has-display-font-family has-x-large-font-size" style="line-height:1.25"><?php esc_html_e( 'Get each new post by email.', 'plainleaf' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size"><?php esc_html_e( 'One email per post. Unsubscribe any time.', 'plainleaf' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:plainleaf/newsletter-form /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
