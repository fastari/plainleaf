<?php
/**
 * Local development helpers. Mounted as a must-use plugin by `npm run dev`.
 * NOT part of the theme zip.
 *
 * @package Plainleaf
 */

// WordPress caches the theme's pattern files until the theme version changes.
// Clear it on every request locally so pattern edits show on reload.
add_action(
	'init',
	function () {
		wp_get_theme()->delete_pattern_cache();
	},
	0
);

/**
 * Local test inbox for the newsletter form.
 *
 * Point Appearance > Theme settings > Form action URL at
 *   http://127.0.0.1:9400/?plainleaf_test_inbox=1
 * then submit the form on the site. The address is stored in a local option and
 * this page lists everything received. Use it only on the local dev site.
 */
add_action(
	'init',
	function () {
		if ( ! isset( $_GET['plainleaf_test_inbox'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- dev only.
			return;
		}
		$received = (array) get_option( 'plainleaf_dev_signups', array() );
		$email    = '';
		foreach ( array( 'email', 'email_address', 'EMAIL' ) as $field ) {
			if ( ! empty( $_POST[ $field ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- dev only.
				$email = sanitize_email( wp_unslash( $_POST[ $field ] ) ); // phpcs:ignore WordPress.Security.NonceVerification -- dev only.
			}
		}
		$extra = array();
		foreach ( $_POST as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification -- dev only.
			if ( ! in_array( $key, array( 'email', 'email_address', 'EMAIL' ), true ) ) {
				$extra[] = sanitize_key( $key ) . '=' . ( is_array( $value ) ? wp_json_encode( map_deep( wp_unslash( $value ), 'sanitize_text_field' ) ) : sanitize_text_field( wp_unslash( $value ) ) );
			}
		}
		if ( $email ) {
			$received[] = array(
				'email' => $email,
				'time'  => current_time( 'mysql' ),
				'extra' => implode( ', ', $extra ),
			);
			update_option( 'plainleaf_dev_signups', $received, false );
		}
		header( 'Content-Type: text/html; charset=utf-8' );
		echo '<!doctype html><meta name="viewport" content="width=device-width"><title>Test inbox</title><body style="font:16px/1.5 system-ui;max-width:40rem;margin:3rem auto;padding:0 1rem">';
		echo '<h1>Local test inbox</h1>';
		if ( $email ) {
			echo '<p style="padding:1rem;background:#F5F5F2;border-radius:.5rem">Received <strong>' . esc_html( $email ) . '</strong>. The form works. With a real newsletter tool, this is where its "check your inbox" page would show.</p>';
		}
		echo '<p>Signups received on this local site:</p><ol>';
		foreach ( array_reverse( $received ) as $row ) {
			echo '<li>' . esc_html( $row['email'] ) . ' <small>(' . esc_html( $row['time'] ) . ( empty( $row['extra'] ) ? '' : ', ' . esc_html( $row['extra'] ) ) . ')</small></li>';
		}
		echo '</ol><p><a href="' . esc_url( home_url( '/' ) ) . '">Back to the site</a></p></body>';
		exit;
	},
	1
);
