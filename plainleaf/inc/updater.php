<?php
/**
 * Theme updates from public GitHub releases. No token, no account.
 *
 * When a new version is released on GitHub, WordPress shows it in Dashboard >
 * Updates like any other theme update, and installs it on its own when
 * auto-updates are on (Appearance > Themes > Plainleaf > Enable auto-updates).
 *
 * How it works: every 6 hours at most, the site asks the public GitHub API for
 * the latest release of the repository below, and offers the release's
 * plainleaf-<version>.zip file as the update. Only zips from that repository's
 * release download address are accepted.
 *
 * Point updates at your own fork with, in wp-config.php:
 *     define( 'PLAINLEAF_UPDATE_REPO', 'your-name/plainleaf' );
 * or turn this off entirely:
 *     define( 'PLAINLEAF_DISABLE_UPDATES', true );
 *
 * Your colors, fonts, logo and text are stored in WordPress, not in the theme,
 * so an update never changes them.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public GitHub repository releases come from, as owner/name.
 *
 * @return string
 */
function plainleaf_updater_repo() {
	$repo = defined( 'PLAINLEAF_UPDATE_REPO' ) ? (string) PLAINLEAF_UPDATE_REPO : 'fastari/plainleaf';
	$repo = (string) apply_filters( 'plainleaf_updater_repo', $repo );
	return preg_match( '#^[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+$#', $repo ) ? $repo : '';
}

/**
 * Whether update checks are on.
 *
 * @return bool
 */
function plainleaf_updater_enabled() {
	$enabled = ! ( defined( 'PLAINLEAF_DISABLE_UPDATES' ) && PLAINLEAF_DISABLE_UPDATES );
	return (bool) apply_filters( 'plainleaf_updater_enabled', $enabled ) && '' !== plainleaf_updater_repo();
}

/**
 * The only address update zips may be downloaded from.
 *
 * @return string
 */
function plainleaf_updater_download_prefix() {
	return 'https://github.com/' . plainleaf_updater_repo() . '/releases/download/';
}

/**
 * The latest release: version, zip address and release page.
 * Cached for 6 hours (1 hour after a failed check).
 *
 * @return array{version:string,package:string,url:string}|null
 */
function plainleaf_updater_latest() {
	$cached = get_site_transient( 'plainleaf_updater_latest' );
	if ( is_array( $cached ) ) {
		return $cached ? $cached : null;
	}

	$data     = null;
	$response = wp_remote_get(
		'https://api.github.com/repos/' . plainleaf_updater_repo() . '/releases/latest',
		array(
			'timeout' => 10,
			'headers' => array(
				'Accept'               => 'application/vnd.github+json',
				'X-GitHub-Api-Version' => '2022-11-28',
				'User-Agent'           => 'plainleaf-theme-updater',
			),
		)
	);
	if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
		$release = json_decode( wp_remote_retrieve_body( $response ), true );
		$version = ltrim( (string) ( is_array( $release ) ? ( $release['tag_name'] ?? '' ) : '' ), 'vV' );
		$assets  = is_array( $release ) && is_array( $release['assets'] ?? null ) ? $release['assets'] : array();
		foreach ( $assets as $asset ) {
			$name = (string) ( $asset['name'] ?? '' );
			$link = (string) ( $asset['browser_download_url'] ?? '' );
			if ( 'plainleaf-' . $version . '.zip' === $name && 0 === strpos( $link, plainleaf_updater_download_prefix() ) ) {
				$data = array(
					'version' => $version,
					'package' => $link,
					'url'     => esc_url_raw( (string) ( $release['html_url'] ?? '' ) ),
				);
				break;
			}
		}
		if ( $data && ! preg_match( '/^[0-9]+(\.[0-9]+)*$/', $data['version'] ) ) {
			$data = null;
		}
	}
	set_site_transient( 'plainleaf_updater_latest', $data ? $data : array(), $data ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	return $data;
}

/**
 * Tell WordPress about a newer release (or that the theme is up to date, which
 * is what makes the "Enable auto-updates" link appear for a theme that isn't
 * on WordPress.org).
 *
 * @param mixed $transient The update_themes transient.
 * @return mixed
 */
function plainleaf_updater_check( $transient ) {
	if ( ! is_object( $transient ) || ! plainleaf_updater_enabled() ) {
		return $transient;
	}
	$slug   = get_template();
	$latest = plainleaf_updater_latest();
	if ( ! $latest ) {
		return $transient;
	}
	$item = array(
		'theme'        => $slug,
		'new_version'  => $latest['version'],
		'url'          => $latest['url'],
		'package'      => $latest['package'],
		'requires'     => '',
		'requires_php' => '',
	);
	if ( version_compare( $latest['version'], PLAINLEAF_VERSION, '>' ) ) {
		$transient->response[ $slug ] = $item;
		unset( $transient->no_update[ $slug ] );
	} else {
		$item['new_version']           = PLAINLEAF_VERSION;
		$transient->no_update[ $slug ] = $item;
		unset( $transient->response[ $slug ] );
	}
	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'plainleaf_updater_check' );

/**
 * Refuse any Plainleaf update package that does not come from the release
 * download address of the configured repository.
 *
 * @param bool|string|WP_Error $reply   Short-circuit value.
 * @param string               $package The package address.
 * @param WP_Upgrader          $upgrader The upgrader.
 * @param array                $hook_extra Extra arguments, including the theme being updated.
 * @return bool|string|WP_Error
 */
function plainleaf_updater_guard_download( $reply, $package, $upgrader = null, $hook_extra = array() ) {
	if ( false !== $reply || ! plainleaf_updater_enabled() || empty( $hook_extra['theme'] ) || get_template() !== $hook_extra['theme'] ) {
		return $reply;
	}
	if ( ! is_string( $package ) || 0 !== strpos( $package, plainleaf_updater_download_prefix() ) ) {
		return new WP_Error( 'plainleaf_update_source', __( 'This theme update does not come from the Plainleaf release page, so it was not installed.', 'plainleaf' ) );
	}
	return $reply;
}
add_filter( 'upgrader_pre_download', 'plainleaf_updater_guard_download', 10, 4 );

/**
 * Check again right after the theme is updated, so the next release is found.
 */
function plainleaf_updater_clear_cache() {
	delete_site_transient( 'plainleaf_updater_latest' );
}
add_action( 'upgrader_process_complete', 'plainleaf_updater_clear_cache' );
