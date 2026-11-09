<?php
/**
 * Title: Browse by topic (links)
 * Slug: plainleaf/topic-links
 * Categories: plainleaf, text
 * Description: A compact row of links to the topics (Posts > Categories).
 * Keywords: topics, categories, topics, browse
 * Viewport Width: 1200
 *
 * @package Plainleaf
 */

$plainleaf_topic_links = plainleaf_topics();
?>
<!-- wp:group {"metadata":{"name":"<?php echo esc_attr__( 'Browse by topic', 'plainleaf' ); ?>"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"46rem"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)">
	<?php if ( '' !== plainleaf_text( 'topics_heading' ) ) : ?>
	<!-- wp:heading {"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
	<h2 class="wp-block-heading pl-label has-sans-font-family has-x-small-font-size"><?php echo esc_html( plainleaf_text( 'topics_heading' ) ); ?></h2>
	<!-- /wp:heading -->
	<?php endif; ?>

	<!-- wp:list {"className":"pl-pills"} -->
	<ul class="wp-block-list pl-pills">
		<?php foreach ( $plainleaf_topic_links as $plainleaf_topic ) : ?>
		<!-- wp:list-item -->
		<li><a href="<?php echo esc_url( $plainleaf_topic['url'] ); ?>"><?php echo esc_html( $plainleaf_topic['name'] ); ?></a></li>
		<!-- /wp:list-item -->
		<?php endforeach; ?>
	</ul>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->
