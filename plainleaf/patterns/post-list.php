<?php
/**
 * Title: Post list (text first)
 * Slug: plainleaf/post-list
 * Categories: plainleaf, query
 * Block Types: core/query
 * Description: Text-first list of posts for the blog and archives: topic, title, excerpt, date and reading time, with pagination.
 * Keywords: blog, archive, list, posts
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<!-- wp:query {"queryId":21,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"className":"pl-post-list","layout":{"type":"default"}} -->
<div class="wp-block-query pl-post-list">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)">
			<!-- wp:post-terms {"term":"category","className":"pl-topic-badge"} /-->
			<!-- wp:post-title {"level":2,"isLink":true,"fontSize":"x-large"} /-->
			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":36} /-->
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
		<p><?php echo esc_html( plainleaf_text( 'list_empty' ) ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->

	<!-- wp:query-pagination {"paginationArrow":"arrow","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<!-- wp:query-pagination-previous {"label":<?php echo plainleaf_text_attr( 'newer_posts' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>} /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next {"label":<?php echo plainleaf_text_attr( 'older_posts' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
