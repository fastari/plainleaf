<?php
/**
 * Title: Listen and share buttons
 * Slug: plainleaf/post-actions
 * Categories: plainleaf
 * Description: Round play (only when the post has audio) and share buttons for a post's date row.
 * Inserter: no
 *
 * @package Plainleaf
 */

$plainleaf_actions = plainleaf_post_actions_html( get_queried_object_id() );
if ( '' === $plainleaf_actions ) {
	return;
}
?>
<!-- wp:html -->
<?php echo $plainleaf_actions; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts in plainleaf_post_actions_html(). ?>
<!-- /wp:html -->
