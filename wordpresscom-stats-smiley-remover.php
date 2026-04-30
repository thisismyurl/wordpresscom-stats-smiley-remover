<?php
/**
 * Plugin Name:       WordPress.com Stats Smiley Remover
 * Plugin URI:        https://thisismyurl.com/plugins/wordpresscom-stats-smiley-remover/
 * Description:       Detaches the WordPress.com Stats / Jetpack Stats footer tracking pixel from your site output.
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
 * retired by Automattic over a decade ago, but Jetpack Stats still emits
 * a tracking pixel via `wp_footer` on AMP requests through the modern
 * `Tracking_Pixel::add_amp_pixel` callback (Jetpack 11.5+), and the
 * legacy `stats_footer` global function still runs on pre-11.5 stacks.
 *
 * This plugin detaches both surfaces. Modern non-AMP Jetpack tracks via
 * a JavaScript file enqueued through `wp_enqueue_scripts` — that path is
 * intentionally untouched, so stats reporting continues to work on
 * non-AMP pages. If Jetpack Stats is not present, every removal here is
 * a safe no-op.
 */
function bootstrap(): void {
	/*
	 * Modern Jetpack registers its `wp_footer` callback from inside
	 * `Main::template_redirect()`, which itself runs at `template_redirect`
	 * priority 1. Hooking our removal on `template_redirect` at PHP_INT_MAX
	 * guarantees we run after Jetpack has registered its hook, so
	 * `remove_action()` matches and succeeds.
	 *
	 * The same callback also runs before `wp_footer` fires, so the pixel
	 * never reaches the rendered page.
	 */
	add_action( 'template_redirect', __NAMESPACE__ . '\\detach_pixel', PHP_INT_MAX );
}

/**
 * Removes every known stats-pixel hook callback.
 *
 * `remove_action()` fails silently when the hook or callback isn't
 * registered, so we don't gate on `function_exists()` for the legacy
 * path — we just call the removal once. The modern path is class-gated
 * because constructing the callable array is wasted work when Jetpack
 * isn't loaded.
 *
 *  1. Legacy WP.com Stats / pre-Jetpack-11.5 — `stats_footer` global function.
 *  2. Modern Jetpack Stats (≥ 11.5) — `Tracking_Pixel::add_amp_pixel` on
 *     `wp_footer` and on `web_stories_print_analytics`, both at
 *     priority 101.
 */
function detach_pixel(): void {
	remove_action( 'wp_footer', 'stats_footer', 101 );

	if ( ! class_exists( 'Automattic\\Jetpack\\Stats\\Tracking_Pixel' ) ) {
		return;
	}

	$callback = [ 'Automattic\\Jetpack\\Stats\\Tracking_Pixel', 'add_amp_pixel' ];

	remove_action( 'wp_footer', $callback, 101 );
	remove_action( 'web_stories_print_analytics', $callback, 101 );
}

bootstrap();
