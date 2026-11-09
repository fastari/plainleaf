<?php
/**
 * Title: A question to sit with
 * Slug: plainleaf/reflection
 * Categories: plainleaf, text
 * Description: The reflection question box that closes each post. Add it at the end of the post and write one question.
 * Keywords: reflection, question, prompt, journal
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"className":"pl-reflection","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"},"border":{"radius":"var(--wp--custom--radius--card)","left":{"color":"var:preset|color|brand","width":"4px","style":"solid"}}},"backgroundColor":"surface","layout":{"type":"default"}} -->
<div class="wp-block-group pl-reflection has-surface-background-color has-background" style="border-radius:var(--wp--custom--radius--card);border-left-color:var(--wp--preset--color--brand);border-left-style:solid;border-left-width:4px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
	<!-- wp:paragraph {"className":"pl-label","fontFamily":"sans","fontSize":"x-small"} -->
	<p class="pl-label has-sans-font-family has-x-small-font-size"><?php esc_html_e( 'A question to sit with', 'plainleaf' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:paragraph {"placeholder":"<?php echo esc_attr__( 'Write one question for the reader to sit with…', 'plainleaf' ); ?>","fontFamily":"display","fontSize":"large"} -->
	<p class="has-display-font-family has-large-font-size"></p>
	<!-- /wp:paragraph -->
	<!-- wp:paragraph {"fontFamily":"sans","fontSize":"small"} -->
	<p class="has-sans-font-family has-small-font-size"><?php esc_html_e( 'Reply to the newsletter and share your answer.', 'plainleaf' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
