<?php
/**
 * Title: Posts as cards (grid)
 * Slug: plainleaf/post-grid
 * Categories: plainleaf, query
 * Block Types: core/query
 * Description: A card grid alternative to the text list: topic, title, story hook and reading time on soft cards. Follows the page's query (Blog, topic pages).
 * Keywords: blog, grid, cards, posts
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

?>
<!-- wp:query {"queryId":53,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"align":"wide","className":"pl-post-grid","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide pl-post-grid">
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
		<!-- wp:group {"className":"pl-card","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group pl-card" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:post-terms {"term":"category","className":"pl-topic-badge"} /-->
			<!-- wp:post-title {"level":2,"isLink":true,"fontSize":"large"} /-->
			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":24,"fontSize":"small"} /-->
			<!-- wp:group {"className":"pl-post-meta","style":{"spacing":{"blockGap":"0.5em"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group pl-post-meta">
				<!-- wp:post-date /-->
				<!-- wp:post-time-to-read {"displayAsRange":false} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
	<!-- wp:query-pagination {"paginationArrow":"arrow","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<!-- wp:query-pagination-previous {"label":"<?php echo esc_attr__( 'Newer posts', 'plainleaf' ); ?>"} /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next {"label":"<?php echo esc_attr__( 'Older posts', 'plainleaf' ); ?>"} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
