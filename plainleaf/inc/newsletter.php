<?php
/**
 * Newsletter signup form, independent of any newsletter tool.
 *
 * One place to connect the form: Appearance > Theme settings > Newsletter.
 * Choose one of:
 *   1. A shortcode from a newsletter plugin, for example [mailpoet_form id="1"]
 *      or [newsletter_form]. The plugin's form is shown inside the theme styling.
 *   2. A form action URL from a hosted tool's "HTML embed" (Kit, Buttondown,
 *      Mailchimp and most others). The theme renders its own accessible form
 *      that posts straight to the tool. No data passes through WordPress.
 * If neither is set, visitors see a short "almost ready" note (or, in the hero,
 * a button to the Newsletter page), and the WordPress dashboard shows a reminder.
 *
 * Use it with the "Newsletter form" block, or the [plainleaf_newsletter_form] shortcode.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep only a single shortcode-looking string.
 *
 * @param string $value Raw value.
 * @return string
 */
function plainleaf_newsletter_sanitize_shortcode( $value ) {
	$value = trim( sanitize_text_field( (string) $value ) );
	return ( '' === $value || preg_match( '/^\[[^\[\]]+\]$/', $value ) ) ? $value : '';
}

/**
 * Field names are things like email, email_address or EMAIL.
 *
 * @param string $value Raw value.
 * @return string
 */
function plainleaf_newsletter_sanitize_field_name( $value ) {
	$value = preg_replace( '/[^A-Za-z0-9_\-\[\]]/', '', (string) $value );
	return '' === $value ? 'email' : $value;
}

/**
 * Like plainleaf_newsletter_sanitize_field_name(), but an empty value stays empty.
 *
 * @param string $value Raw value.
 * @return string
 */
function plainleaf_newsletter_sanitize_optional_field_name( $value ) {
	return (string) preg_replace( '/[^A-Za-z0-9_\-\[\]]/', '', (string) $value );
}

/**
 * Button text, from the settings, with a default.
 *
 * @return string
 */
function plainleaf_newsletter_button_text() {
	$text = trim( (string) get_option( 'plainleaf_newsletter_button', '' ) );
	return '' === $text ? __( 'Subscribe', 'plainleaf' ) : $text;
}

/**
 * Where on the site this form is, for the optional hidden "signup source" field.
 *
 * @param string $variant Form variant ('hero' or 'default').
 * @return string One of home-hero, home-box, post-end, newsletter-page, start-here, other.
 */
function plainleaf_newsletter_signup_source( $variant ) {
	if ( is_front_page() ) {
		$source = 'hero' === $variant ? 'home-hero' : 'home-box';
	} elseif ( is_singular( 'post' ) ) {
		$source = 'post-end';
	} elseif ( is_page( 'newsletter' ) ) {
		$source = 'newsletter-page';
	} elseif ( is_page( 'start-here' ) ) {
		$source = 'start-here';
	} else {
		$source = 'other';
	}
	/**
	 * Filter the signup source value sent to the newsletter tool.
	 *
	 * @param string $source  Source label.
	 * @param string $variant Form variant.
	 */
	return (string) apply_filters( 'plainleaf_newsletter_signup_source', $source, $variant );
}

/**
 * Where to send people after a successful signup: the "After signup" setting,
 * otherwise a published page with the slug check-your-inbox, otherwise nowhere
 * (the newsletter tool shows its own page).
 *
 * @return string URL or empty string.
 */
function plainleaf_newsletter_redirect_url() {
	$url = trim( (string) get_option( 'plainleaf_newsletter_redirect', '' ) );
	if ( '' !== $url ) {
		return $url;
	}
	$page = get_page_by_path( 'check-your-inbox' );
	if ( $page && 'publish' === $page->post_status ) {
		return (string) get_permalink( $page );
	}
	return '';
}

/**
 * Build the signup form HTML.
 *
 * @param array $args {
 *     Optional.
 *     @type string $variant 'default', or 'hero' for the compact one-line form
 *                           with a visually hidden label.
 * }
 * @return string
 */
function plainleaf_newsletter_form_html( $args = array() ) {
	$variant   = ( isset( $args['variant'] ) && 'hero' === $args['variant'] ) ? 'hero' : 'default';
	$shortcode = (string) get_option( 'plainleaf_newsletter_shortcode', '' );
	$action    = (string) get_option( 'plainleaf_newsletter_action', '' );

	if ( '' !== $shortcode ) {
		return '<div class="pl-signup__form pl-signup__form--plugin">' . do_shortcode( $shortcode ) . '</div>';
	}

	if ( '' !== $action ) {
		$field        = (string) get_option( 'plainleaf_newsletter_email_field', 'email' );
		$source_field = (string) get_option( 'plainleaf_newsletter_source_field', '' );
		$id           = wp_unique_id( 'pl-email-' );
		$hidden       = '' === $source_field ? '' : sprintf(
			'<input type="hidden" name="%1$s" value="%2$s">',
			esc_attr( $source_field ),
			esc_attr( plainleaf_newsletter_signup_source( $variant ) )
		);
		$redirect = plainleaf_newsletter_redirect_url();
		if ( '' !== $redirect ) {
			plainleaf_newsletter_needs_script( true );
		}
		return sprintf(
			'<form class="pl-signup__form pl-signup__form--%1$s" action="%2$s" method="post"%10$s>' .
				'<label for="%3$s"%4$s>%5$s</label>' .
				'<input type="email" id="%3$s" name="%6$s" autocomplete="email" required placeholder="%7$s">' .
				'%9$s' .
				'<button type="submit">%8$s</button>' .
				'<p class="pl-signup__message" role="status" aria-live="polite" hidden></p>' .
			'</form>',
			esc_attr( $variant ),
			esc_url( $action ),
			esc_attr( $id ),
			'hero' === $variant ? ' class="screen-reader-text"' : '',
			esc_html__( 'Your email address', 'plainleaf' ),
			esc_attr( $field ),
			esc_attr( plainleaf_text( 'email_placeholder' ) ),
			esc_html( plainleaf_newsletter_button_text() ),
			$hidden, // Built with escaping above.
			'' === $redirect ? '' : ' data-pl-redirect="' . esc_url( $redirect ) . '"'
		);
	}

	if ( 'hero' === $variant ) {
		// No tool connected yet: keep a call to action in the hero instead of a notice.
		$html = sprintf(
			'<div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="%s">%s</a></div></div>',
			esc_url( home_url( '/newsletter/' ) ),
			esc_html( plainleaf_newsletter_button_text() )
		);
	} else {
		$html = '<p class="pl-signup__pending">' . esc_html( plainleaf_text( 'signup_pending' ) ) . '</p>';
	}
	return $html;
}

/**
 * Is a newsletter tool connected?
 *
 * @return bool
 */
function plainleaf_newsletter_is_connected() {
	return '' !== (string) get_option( 'plainleaf_newsletter_shortcode', '' ) || '' !== (string) get_option( 'plainleaf_newsletter_action', '' );
}

/**
 * Reminder in the WordPress dashboard (never on the public site) while no
 * newsletter tool is connected.
 */
function plainleaf_newsletter_admin_notice() {
	if ( plainleaf_newsletter_is_connected() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'appearance_page_ste-settings' ), true ) ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
		esc_html__( 'The newsletter signup is not connected yet. Until it is, the site shows a button to the Newsletter page instead of an email box.', 'plainleaf' ),
		esc_url( admin_url( 'themes.php?page=pl-settings' ) ),
		esc_html__( 'Connect a newsletter tool', 'plainleaf' )
	);
}
add_action( 'admin_notices', 'plainleaf_newsletter_admin_notice' );

/**
 * [plainleaf_newsletter_form] shortcode. Accepts variant="hero".
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function plainleaf_newsletter_shortcode( $atts = array() ) {
	$atts = shortcode_atts( array( 'variant' => 'default' ), $atts, 'plainleaf_newsletter_form' );
	return '<div class="pl-signup">' . plainleaf_newsletter_form_html( $atts ) . '</div>';
}
add_shortcode( 'plainleaf_newsletter_form', 'plainleaf_newsletter_shortcode' );

/**
 * Remember whether a page printed a form that needs the signup script.
 *
 * @param bool $set Pass true to mark the script as needed.
 * @return bool
 */
function plainleaf_newsletter_needs_script( $set = false ) {
	static $needed = false;
	if ( $set ) {
		$needed = true;
	}
	return $needed;
}

/**
 * Small script that sends the signup in the background and then goes to the
 * site's own "Check your inbox" page, so visitors never land on the newsletter
 * tool's page. Shows the tool's error (for example an invalid address) in the form.
 * If anything unexpected happens, the form is submitted the normal way instead,
 * so a signup is never lost. Printed only on pages that have a form.
 */
function plainleaf_newsletter_print_script() {
	if ( ! plainleaf_newsletter_needs_script() ) {
		return;
	}
	$messages = array(
		'sending' => plainleaf_text( 'signup_sending' ),
		'invalid' => plainleaf_text( 'signup_invalid' ),
	);
	?>
<script>
( function () {
	var t = <?php echo wp_json_encode( $messages ); ?>;
	document.querySelectorAll( 'form[data-pl-redirect]' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function ( event ) {
			if ( ! window.fetch || ! window.FormData || form.dataset.plainleafNative ) {
				return;
			}
			event.preventDefault();
			var button = form.querySelector( 'button[type="submit"]' );
			var message = form.querySelector( '.pl-signup__message' );
			var label = button ? button.textContent : '';
			var native = function () {
				form.dataset.plainleafNative = '1';
				form.submit();
			};
			if ( button ) {
				button.disabled = true;
				button.textContent = t.sending;
			}
			fetch( form.action, { method: 'POST', body: new FormData( form ), headers: { Accept: 'application/json' } } )
				.then( function ( response ) {
					if ( ! response.ok ) {
						throw new Error( 'HTTP ' + response.status );
					}
					return response.text();
				} )
				.then( function ( body ) {
					var data = null;
					try {
						data = JSON.parse( body );
					} catch ( e ) {}
					if ( data && data.status && 'success' !== data.status ) {
						if ( button ) {
							button.disabled = false;
							button.textContent = label;
						}
						if ( message ) {
							message.textContent = t.invalid;
							message.hidden = false;
						}
						var input = form.querySelector( 'input[type="email"]' );
						if ( input ) {
							input.setAttribute( 'aria-invalid', 'true' );
							input.focus();
						}
						return;
					}
					window.location.assign( form.dataset.plainleafRedirect );
				} )
				.catch( native );
		} );
	} );
} )();
</script>
	<?php
}
add_action( 'wp_footer', 'plainleaf_newsletter_print_script', 20 );

