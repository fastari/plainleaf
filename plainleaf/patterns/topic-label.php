<?php
/**
 * Title: Topic label
 * Slug: plainleaf/topic-label
 * Categories: plainleaf
 * Description: The small label above a topic's name, from Appearance > Site text.
 * Inserter: no
 *
 * @package Plainleaf
 */

if ( '' === plainleaf_text( 'topic_label' ) ) {
	return;
}
?>
<!-- wp:paragraph {"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
<p class="pl-label has-sans-font-family has-x-small-font-size"><?php echo esc_html( plainleaf_text( 'topic_label' ) ); ?></p>
<!-- /wp:paragraph -->
