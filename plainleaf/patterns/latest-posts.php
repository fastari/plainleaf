<?php
/**
 * Title: Latest posts (text list)
 * Slug: plainleaf/latest-posts
 * Categories: plainleaf, query
 * Block Types: core/query
 * Description: The three newest posts as a calm text list: topic, title, excerpt, date and reading time.
 * Keywords: latest, recent, posts, blog
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Latest posts', 'plainleaf' ); ?>"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Latest writing', 'plainleaf' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"style":{"typography":{"fontWeight":"500"}},"fontFamily":"sans","fontSize":"small"} -->
		<p class="has-sans-font-family has-small-font-size" style="font-weight:500"><a href="<?php echo esc_url( plainleaf_blog_url() ); ?>"><?php echo esc_html( plainleaf_text( 'all_posts' ) ); ?><span aria-hidden="true"> &rarr;</span></a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":11,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false,"taxQuery":null,"parents":[]},"align":"wide","className":"pl-post-list","layout":{"type":"default"}} -->
	<div class="wp-block-query alignwide pl-post-list">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"46rem","justifyContent":"left"}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)">
				<!-- wp:post-terms {"term":"category","className":"pl-topic-badge"} /-->
				<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"x-large"} /-->
				<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":30} /-->
				<!-- wp:group {"className":"pl-post-meta","style":{"spacing":{"blockGap":"0.5em"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
				<div class="wp-block-group pl-post-meta">
					<!-- wp:post-date /-->
					<!-- wp:post-time-to-read {"displayAsRange":false} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p><?php echo esc_html( plainleaf_text( 'home_empty' ) ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
