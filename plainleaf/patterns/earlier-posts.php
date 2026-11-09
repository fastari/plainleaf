<?php
/**
 * Title: Earlier stories (title list)
 * Slug: plainleaf/earlier-posts
 * Categories: plainleaf, query
 * Block Types: core/query
 * Description: A quiet list of older posts, title and date only, skipping the two shown as featured.
 * Keywords: older, archive, list, posts
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Earlier stories', 'plainleaf' ); ?>"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"46rem"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
	<?php if ( '' !== plainleaf_text( 'earlier_heading' ) ) : ?>
	<!-- wp:heading {"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
	<h2 class="wp-block-heading pl-label has-sans-font-family has-x-small-font-size"><?php echo esc_html( plainleaf_text( 'earlier_heading' ) ); ?></h2>
	<!-- /wp:heading -->
	<?php endif; ?>

	<!-- wp:query {"queryId":32,"query":{"perPage":4,"pages":0,"offset":2,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":null,"parents":[]},"className":"pl-title-list","layout":{"type":"default"}} -->
	<div class="wp-block-query pl-title-list">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
				<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large"} /-->
				<!-- wp:post-date /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

	<!-- wp:paragraph {"className":"pl-tap","style":{"typography":{"fontWeight":"500"}},"fontFamily":"sans","fontSize":"small"} -->
	<p class="pl-tap has-sans-font-family has-small-font-size" style="font-weight:500"><a href="<?php echo esc_url( plainleaf_blog_url() ); ?>"><?php echo esc_html( plainleaf_text( 'all_posts' ) ); ?><span aria-hidden="true"> &rarr;</span></a></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
