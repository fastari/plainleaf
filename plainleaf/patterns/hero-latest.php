<?php
/**
 * Title: Hero: with latest story
 * Slug: plainleaf/hero-latest
 * Categories: plainleaf, banner, query
 * Description: Brand-colored band with the promise on the left and a card showing the newest story on the right. Stacks on phones.
 * Keywords: hero, banner, intro, home, latest, featured
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Hero: with latest story', 'plainleaf' ); ?>"},"align":"full","className":"pl-intro","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"margin":{"top":"0","bottom":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|on-brand"}}}},"backgroundColor":"brand","textColor":"on-brand","layout":{"type":"constrained"}} -->
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
			<!-- wp:query {"queryId":41,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":null,"parents":[]},"layout":{"type":"default"}} -->
			<div class="wp-block-query">
				<!-- wp:post-template {"layout":{"type":"default"}} -->
					<!-- wp:group {"className":"pl-hero-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"var(--wp--custom--radius--card)"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}},"backgroundColor":"base","textColor":"contrast","layout":{"type":"default"}} -->
					<div class="wp-block-group pl-hero-card has-contrast-color has-base-background-color has-text-color has-background has-link-color" style="border-radius:var(--wp--custom--radius--card);padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
						<!-- wp:paragraph {"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
						<p class="pl-label has-sans-font-family has-x-small-font-size"><?php esc_html_e( 'Latest story', 'plainleaf' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:post-terms {"term":"category","className":"pl-topic-badge"} /-->
						<!-- wp:post-title {"level":2,"isLink":true,"style":{"typography":{"lineHeight":"1.15"}},"fontSize":"x-large"} /-->
						<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":28,"fontSize":"small"} /-->
						<!-- wp:group {"className":"pl-meta-row","style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
						<div class="wp-block-group pl-meta-row">
							<!-- wp:post-time-to-read {"displayAsRange":false} /-->
							<!-- wp:read-more {"content":<?php echo plainleaf_text_attr( 'read_more' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>,"className":"pl-read-more"} /-->
						</div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:group -->
				<!-- /wp:post-template -->
			</div>
			<!-- /wp:query -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
