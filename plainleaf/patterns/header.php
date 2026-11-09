<?php
/**
 * Title: Header
 * Slug: plainleaf/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Sticky header with the wordmark on the left and the main navigation on the right.
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:group {"align":"wide","className":"pl-header-row","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide pl-header-row">
		<!-- wp:plainleaf/wordmark {"size":"header"} /-->

		<!-- wp:navigation {"ariaLabel":"<?php echo esc_attr_x( 'Main', 'navigation label', 'plainleaf' ); ?>","overlayMenu":"mobile","overlayBackgroundColor":"base","overlayTextColor":"contrast","icon":"menu","className":"pl-main-nav","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} -->
			<!-- wp:navigation-link {"label":<?php echo plainleaf_text_attr( 'nav_home' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>,"url":"<?php echo esc_url( home_url( '/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<?php if ( plainleaf_page_exists( 'start-here' ) ) : ?>
			<!-- wp:navigation-link {"label":<?php echo plainleaf_text_attr( 'nav_start' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>,"url":"<?php echo esc_url( home_url( '/start-here/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<?php endif; ?>
			<!-- wp:navigation-link {"label":<?php echo plainleaf_text_attr( 'nav_blog' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>,"url":"<?php echo esc_url( plainleaf_blog_url() ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<?php if ( plainleaf_page_exists( 'about' ) ) : ?>
			<!-- wp:navigation-link {"label":<?php echo plainleaf_text_attr( 'nav_about' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>,"url":"<?php echo esc_url( home_url( '/about/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<?php endif; ?>
			<?php if ( plainleaf_page_exists( 'newsletter' ) ) : ?>
			<!-- wp:navigation-link {"label":<?php echo plainleaf_text_attr( 'nav_newsletter' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block attribute JSON. ?>,"url":"<?php echo esc_url( home_url( '/newsletter/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
			<?php endif; ?>
		<!-- /wp:navigation -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
