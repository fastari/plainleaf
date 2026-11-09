<?php
/**
 * Title: Related posts
 * Slug: plainleaf/related-posts
 * Categories: plainleaf, query
 * Description: Up to three other posts from the same topic. Hidden when there are none.
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<!-- wp:query {"queryId":51,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"pl-related","layout":{"type":"default"}} -->
<div class="wp-block-query pl-related">
	<?php if ( '' !== plainleaf_text( 'related_heading' ) ) : ?>
	<!-- wp:heading {"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
	<h2 class="wp-block-heading pl-label has-sans-font-family has-x-small-font-size"><?php echo esc_html( plainleaf_text( 'related_heading' ) ); ?></h2>
	<!-- /wp:heading -->
	<?php endif; ?>
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"14rem"}} -->
		<!-- wp:group {"className":"pl-card","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group pl-card" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
			<!-- wp:post-terms {"term":"category","className":"pl-topic-badge"} /-->
			<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /-->
			<!-- wp:group {"className":"pl-post-meta","style":{"spacing":{"blockGap":"0.5em"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group pl-post-meta">
				<!-- wp:post-date /-->
				<!-- wp:post-time-to-read {"displayAsRange":false} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
