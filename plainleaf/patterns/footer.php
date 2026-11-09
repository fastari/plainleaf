<?php
/**
 * Title: Footer
 * Slug: plainleaf/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Footer with the logo or site title, the one-line promise, navigation, social links (when set) and the theme credit (can be turned off).
 * Inserter: no
 *
 * @package Plainleaf
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"},"margin":{"top":"0"}},"border":{"top":{"color":"var:preset|color|line","width":"1px","style":"solid"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="border-top-color:var(--wp--preset--color--line);border-top-style:solid;border-top-width:1px;margin-top:0;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"},"layout":{"selfStretch":"fixed","flexSize":"24rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:plainleaf/wordmark {"size":"footer","showIcon":false} /-->
			<?php if ( '' !== plainleaf_text( 'footer_tagline' ) ) : ?>
			<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"medium"} -->
			<p class="has-medium-font-size" style="line-height:1.5"><?php echo plainleaf_text_lines( 'footer_tagline' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per line. ?></p>
			<!-- /wp:paragraph -->
			<?php endif; ?>
			<?php if ( '' !== plainleaf_text( 'footer_note' ) ) : ?>
			<!-- wp:paragraph {"className":"pl-name-note","style":{"typography":{"lineHeight":"1.55"}},"textColor":"muted","fontFamily":"sans","fontSize":"x-small"} -->
			<p class="pl-name-note has-muted-color has-text-color has-sans-font-family has-x-small-font-size" style="line-height:1.55"><?php echo '' !== plainleaf_text( 'footer_note_title' ) ? '<strong>' . esc_html( plainleaf_text( 'footer_note_title' ) ) . '</strong> ' : ''; ?><?php echo plainleaf_text_lines( 'footer_note' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per line. ?></p>
			<!-- /wp:paragraph -->
			<?php endif; ?>
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:navigation {"ariaLabel":"<?php echo esc_attr_x( 'Footer', 'navigation label', 'plainleaf' ); ?>","overlayMenu":"never","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
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

			<?php if ( plainleaf_social_links() ) : // Only when at least one profile is set (Appearance > Theme settings > Social links). ?>
			<!-- wp:social-links {"size":"has-normal-icon-size","className":"is-style-logos-only pl-social","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
			<ul class="wp-block-social-links has-normal-icon-size is-style-logos-only pl-social">
				<?php foreach ( plainleaf_social_links() as $plainleaf_service => $plainleaf_link ) : ?>
				<?php
				$plainleaf_social_attrs = array(
					'url'     => esc_url_raw( $plainleaf_link['url'] ),
					'service' => $plainleaf_service,
					'label'   => $plainleaf_link['label'],
				);
				?>
				<!-- wp:social-link <?php echo serialize_block_attributes( $plainleaf_social_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- serialize_block_attributes() encodes for block comments. ?> /-->
				<?php endforeach; ?>
			</ul>
			<!-- /wp:social-links -->
			<?php endif; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"padding":{"top":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30"},"border":{"top":{"color":"var:preset|color|line","width":"1px","style":"solid"}}},"textColor":"muted","fontSize":"x-small","fontFamily":"sans","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide has-muted-color has-text-color has-sans-font-family has-x-small-font-size" style="border-top-color:var(--wp--preset--color--line);border-top-style:solid;border-top-width:1px;margin-top:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--30)">
		<!-- wp:paragraph -->
		<p>
		<?php
		/* translators: 1: current year, 2: site name. */
		echo esc_html( sprintf( __( '© %1$s %2$s', 'plainleaf' ), wp_date( 'Y' ), get_bloginfo( 'name' ) ) );
		?>
		</p>
		<!-- /wp:paragraph -->
		<?php if ( get_privacy_policy_url() ) : // Only link to the privacy page once it is published (Settings > Privacy). ?>
		<!-- wp:paragraph {"className":"pl-tap"} -->
		<p class="pl-tap"><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php echo esc_html( plainleaf_text( 'footer_privacy' ) ); ?></a></p>
		<!-- /wp:paragraph -->
		<?php endif; ?>
		<?php if ( plainleaf_show_credit() ) : // Appearance > Theme settings > Theme credit. ?>
		<!-- wp:paragraph {"className":"pl-credit pl-tap"} -->
		<p class="pl-credit pl-tap">
		<?php
		printf(
			/* translators: %s: link to the theme author's website. */
			esc_html__( 'Theme by %s', 'plainleaf' ),
			'<a href="' . esc_url( 'https://fastari.com/' ) . '">Fastari</a>'
		);
		?>
		</p>
		<!-- /wp:paragraph -->
		<?php endif; ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
