<?php
/**
 * Title: Featured stories (journal style)
 * Slug: plainleaf/featured-posts
 * Categories: plainleaf, query
 * Block Types: core/query
 * Description: The newest two posts shown large, journal style: centred date, topic and reading time, a big title, the story hook and a "Read the story" link.
 * Keywords: latest, featured, posts, journal, blog
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

?>
<!-- wp:query {"queryId":31,"query":{"perPage":2,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":null,"parents":[]},"align":"wide","className":"pl-featured","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide pl-featured">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
		<!-- wp:group {"className":"pl-featured__item","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"46rem"}} -->
		<div class="wp-block-group pl-featured__item" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
			<!-- wp:group {"className":"pl-meta-row","style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
			<div class="wp-block-group pl-meta-row">
				<!-- wp:post-date {"textColor":"muted"} /-->
				<!-- wp:post-terms {"term":"category","className":"pl-topic-badge"} /-->
				<!-- wp:post-time-to-read {"displayAsRange":false} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:post-title {"textAlign":"center","level":2,"isLink":true,"style":{"typography":{"lineHeight":"1.1"}},"fontSize":"display"} /-->

			<!-- wp:group {"layout":{"type":"constrained","contentSize":"33rem"}} -->
			<div class="wp-block-group">
				<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":55} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:read-more {"content":<?php echo plainleaf_text_attr( 'read_more' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>,"className":"pl-read-more"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html( plainleaf_text( 'home_empty' ) ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
