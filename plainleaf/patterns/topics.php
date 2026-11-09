<?php
/**
 * Title: Topics
 * Slug: plainleaf/topics
 * Categories: plainleaf, text
 * Description: The topics from Posts > Categories, each with its description and a link.
 * Keywords: topics, categories, topics
 * Viewport Width: 1400
 *
 * @package Plainleaf
 */

$plainleaf_topics = array_values( plainleaf_topics() );
?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Topics', 'plainleaf' ); ?>"},"align":"full","className":"pl-topics","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull pl-topics" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"40rem","justifyContent":"left"}} -->
	<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--50)">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'What I write about', 'plainleaf' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'These are the threads that run through everything here.', 'plainleaf' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":<?php echo (int) max( 1, min( 5, count( $plainleaf_topics ) ) ); ?>,"minimumColumnWidth":"11rem"}} -->
	<div class="wp-block-group alignwide">
		<?php foreach ( $plainleaf_topics as $plainleaf_index => $plainleaf_topic ) : ?>
		<!-- wp:group {"className":"pl-topic","style":{"spacing":{"padding":{"top":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group pl-topic" style="padding-top:var(--wp--preset--spacing--30)">
			<!-- wp:paragraph {"className":"pl-topic__number","style":{"typography":{"fontWeight":"600"}},"textColor":"brand-ink","fontFamily":"sans","fontSize":"x-small"} -->
			<p class="pl-topic__number has-brand-ink-color has-text-color has-sans-font-family has-x-small-font-size" style="font-weight:600"><?php echo esc_html( sprintf( '%02d', $plainleaf_index + 1 ) ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":3,"fontSize":"large"} -->
			<h3 class="wp-block-heading has-large-font-size"><a href="<?php echo esc_url( $plainleaf_topic['url'] ); ?>"><?php echo esc_html( $plainleaf_topic['name'] ); ?></a></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.55"}},"fontSize":"small"} -->
			<p class="has-small-font-size" style="line-height:1.55"><?php echo esc_html( $plainleaf_topic['description'] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
