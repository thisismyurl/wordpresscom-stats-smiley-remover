<?php
/**
 * Plugin Name:       WordPress.com Stats Smiley Remover
 * Plugin URI:        https://thisismyurl.com/plugins/wordpresscom-stats-smiley-remover/
 * Description:       Removes the WordPress.com Stats / Jetpack Stats footer tracking pixel from your site output.
 * Version:           16.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Christopher Ross
 * Author URI:        https://thisismyurl.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wordpresscom-stats-smiley-remover
 *
 * @package ThisIsMyURL\WPSmileyRemover
 */

declare( strict_types = 1 );

namespace ThisIsMyURL\WPSmileyRemover;

defined( 'ABSPATH' ) || exit;

const VERSION = '16.0.0';

/**
 * Detach the WordPress.com Stats / Jetpack Stats footer pixel.
 *
 * The original 2009 plugin removed a visible smiley character that
 * WordPress.com Stats injected into the site footer. That smiley was
 * retired by Automattic over a decade ago, but the modern Jetpack Stats
 * module still emits a tracking pixel via `wp_footer` — both as a
 * `<noscript>` image and through the `Tracking_Pixel` class in newer
 * Jetpack releases.
 *
 * This plugin detaches both surfaces so stats can run server-side
 * without injecting a pixel into rendered markup. If Jetpack Stats is
 * not present, every call here is a safe no-op.
 */
function bootstrap(): void {
	add_action( 'wp_loaded', __NAMESPACE__ . '\\detach_pixel', PHP_INT_MAX );
}

/**
 * Removes every known stats-pixel hook callback.
 *
 * `remove_action()` fails silently when the hook or callback isn't
 * registered, so we don't gate on `function_exists()` — we just call
 * the removals once. Order:
 *
 *  1. Legacy WP.com Stats / older Jetpack — `stats_footer` global function.
 *  2. Modern Jetpack Stats (≥ 11.5) — `Automattic\Jetpack\Stats\Tracking_Pixel::add_to_footer`.
 */
function detach_pixel(): void {
	remove_action( 'wp_footer', 'stats_footer', 101 );

	$modern_callback = [ 'Automattic\\Jetpack\\Stats\\Tracking_Pixel', 'add_to_footer' ];
	if ( class_exists( $modern_callback[0] ) ) {
		remove_action( 'wp_footer', $modern_callback, 101 );
	}
}

bootstrap();
