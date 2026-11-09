<?php
/**
 * Title: Topic filter
 * Slug: plainleaf/topic-filter
 * Categories: plainleaf
 * Description: A row of links: All posts plus each topic (Posts > Categories). The page being viewed is highlighted.
 * Inserter: no
 *
 * @package Plainleaf
 */

$plainleaf_filter = array( '' => plainleaf_text( 'filter_all' ) );
foreach ( plainleaf_topics() as $plainleaf_topic_slug => $plainleaf_topic ) {
	$plainleaf_filter[ $plainleaf_topic_slug ] = $plainleaf_topic['name'];
}
?>
<!-- wp:html -->
<nav class="pl-filter" aria-label="<?php echo esc_attr__( 'Topics', 'plainleaf' ); ?>">
	<ul>
		<?php foreach ( $plainleaf_filter as $plainleaf_slug => $plainleaf_label ) : ?>
		<li><a href="<?php echo esc_url( '' === $plainleaf_slug ? plainleaf_blog_url() : plainleaf_category_url( $plainleaf_slug ) ); ?>"<?php echo plainleaf_filter_current_attr( $plainleaf_slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute string. ?>><?php echo esc_html( $plainleaf_label ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
<!-- /wp:html -->
