<?php
/**
 * Shared hero copy, included by the hero patterns. Not a pattern itself.
 *
 * Set $plainleaf_hero_form = false before including to show the two buttons instead of
 * the email form (used by the heroes that already have a card on the right).
 *
 * @package Plainleaf
 */

$plainleaf_hero_form = isset( $plainleaf_hero_form ) ? (bool) $plainleaf_hero_form : true;
?>
		<!-- wp:heading {"level":1,"className":"pl-hero-title","style":{"typography":{"lineHeight":"1.05"}},"fontSize":"display"} -->
		<h1 class="wp-block-heading pl-hero-title has-display-font-size" style="line-height:1.05"><?php echo plainleaf_text_lines( 'hero_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per line. ?></h1>
		<!-- /wp:heading -->

<?php if ( '' !== plainleaf_text( 'hero_subtitle' ) ) : ?>
		<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"large"} -->
		<p class="has-large-font-size" style="line-height:1.5"><?php echo plainleaf_text_lines( 'hero_subtitle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per line. ?></p>
		<!-- /wp:paragraph -->
<?php endif; ?>

<?php if ( $plainleaf_hero_form ) : ?>
		<!-- wp:plainleaf/newsletter-form {"variant":"hero","style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} /-->

	<?php if ( '' !== plainleaf_text( 'hero_promise' ) ) : ?>
		<!-- wp:paragraph {"className":"pl-hero-promise","style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"fontFamily":"sans","fontSize":"small"} -->
		<p class="pl-hero-promise has-sans-font-family has-small-font-size" style="margin-top:var(--wp--preset--spacing--20)"><?php echo esc_html( plainleaf_text( 'hero_promise' ) ); ?></p>
		<!-- /wp:paragraph -->
	<?php endif; ?>
<?php else : ?>
		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>"><?php echo esc_html( plainleaf_newsletter_button_text() ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/start-here/' ) ); ?>"><?php echo esc_html( plainleaf_text( 'nf_start' ) ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
<?php endif; ?>

<?php if ( '' !== plainleaf_text( 'author_name' ) ) : // No byline until an author name is set (Appearance > Site text > Author). ?>
		<!-- wp:group {"className":"pl-hero-byline","style":{"spacing":{"blockGap":"0.75rem","margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group pl-hero-byline" style="margin-top:var(--wp--preset--spacing--40)">
			<!-- wp:plainleaf/author-photo {"size":56} /-->
			<!-- wp:paragraph {"className":"pl-byline","fontFamily":"sans","fontSize":"small"} -->
			<p class="pl-byline has-sans-font-family has-small-font-size">
			<?php echo plainleaf_byline_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts. ?>
			</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
<?php endif; ?>
