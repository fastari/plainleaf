<?php
/**
 * Title: Author box
 * Slug: plainleaf/author-box
 * Categories: plainleaf
 * Description: The author's photo with a short line and a link to the About page. Shown at the end of every post once an author name is set.
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<?php
// No author box until an author name is set.
if ( '' === plainleaf_text( 'author_name' ) ) {
	return;
}
?>
<!-- wp:group {"className":"pl-author-box","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"top":{"color":"var:preset|color|line","width":"1px","style":"solid"},"bottom":{"color":"var:preset|color|line","width":"1px","style":"solid"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group pl-author-box" style="border-top-color:var(--wp--preset--color--line);border-top-style:solid;border-top-width:1px;border-bottom-color:var(--wp--preset--color--line);border-bottom-style:solid;border-bottom-width:1px;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)">
	<!-- wp:plainleaf/author-photo {"size":72} /-->
	<!-- wp:paragraph {"fontFamily":"sans","fontSize":"small"} -->
	<p class="has-sans-font-family has-small-font-size">
	<?php echo plainleaf_byline_html( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts. ?>
	</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
