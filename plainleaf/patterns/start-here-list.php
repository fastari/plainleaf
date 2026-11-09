<?php
/**
 * Title: Start here list
 * Slug: plainleaf/start-here-list
 * Categories: plainleaf, query
 * Description: The posts tagged "start-here", numbered in reading order (oldest first).
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<!-- wp:query {"queryId":52,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"asc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"pl-start-here","layout":{"type":"default"}} -->
<div class="wp-block-query pl-start-here">
	<!-- wp:post-template {"className":"pl-start-list","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)">
			<!-- wp:post-terms {"term":"category","className":"pl-topic-badge"} /-->
			<!-- wp:post-title {"level":2,"isLink":true,"fontSize":"x-large"} /-->
			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":36} /-->
			<!-- wp:post-time-to-read {"displayAsRange":false} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
	<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p><?php echo esc_html( plainleaf_text( 'start_empty' ) ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
