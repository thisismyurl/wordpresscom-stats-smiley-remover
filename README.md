# WordPress.com Stats Smiley Remover

Single-file WordPress plugin that detaches the WordPress.com Stats / Jetpack Stats footer tracking pixel from your rendered HTML.

[![WordPress.org](https://img.shields.io/wordpress/plugin/installs/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/)
[![Rating](https://img.shields.io/wordpress/plugin/r/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/#reviews)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL_v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)

---

## Why the name?

I shipped this plugin in 2009 to hide a visible smiley character that the WordPress.com Stats plugin injected into the footer of every page. The original used a CSS rule. Automattic retired the smiley around 2012, so that rule stopped doing anything useful a long time ago.

Jetpack Stats today still injects a footer tracking pixel — as a `<noscript>` image through the legacy `stats_footer` callback, and through `Automattic\Jetpack\Stats\Tracking_Pixel::add_to_footer` since Jetpack 11.5. Same shape of problem, different decade. I rewrote the plugin to do for Jetpack Stats what the original did for WP.com Stats.

The slug and listing name are preserved for continuity with the original wp.org page and the inbound links it has accumulated since 2009.

## What it does

- Detaches the legacy `stats_footer` callback from `wp_footer` (older WP.com Stats and pre-11.5 Jetpack).
- Detaches `Automattic\Jetpack\Stats\Tracking_Pixel::add_to_footer` (Jetpack 11.5 and later).
- Safe no-op when Jetpack Stats is not installed or active.

Small, single-purpose plugin. No settings page, no admin chrome, no tracking. Activate it and it works. Deactivate it and it leaves no trace.

Originally published 2009, rewritten 2026 for current WordPress and current Jetpack Stats.

## Requirements

- WordPress 6.4 or later.
- PHP 7.4 or later.
- Jetpack Stats (optional — plugin is a no-op without it).

## Installation

1. Install through the [WordPress plugin directory](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/) or upload the plugin folder to `wp-content/plugins/`.
2. Activate it from **Plugins** in WordPress admin.
3. If Jetpack Stats is active, the footer tracking pixel is detached automatically.

## How it works

The whole plugin is one function. Both removals run once on `wp_loaded` at `PHP_INT_MAX` so any Jetpack code that registers the hooks has finished by then:

```php
function detach_pixel(): void {
    remove_action( 'wp_footer', 'stats_footer', 101 );

    $modern_callback = [ 'Automattic\\Jetpack\\Stats\\Tracking_Pixel', 'add_to_footer' ];
    if ( class_exists( $modern_callback[0] ) ) {
        remove_action( 'wp_footer', $modern_callback, 101 );
    }
}
```

`remove_action()` returns silently when the hook callback isn't registered, so the legacy path doesn't need a `function_exists()` guard. The modern path is class-gated because referencing a non-autoloaded class as a string array is fine, but I'd rather not call `remove_action()` for a callback that provably can't exist.

## Will this break my Jetpack Stats reporting?

In most modern Jetpack configurations, no — view tracking happens server-side or through the Jetpack site connection. The footer pixel is a fallback. If you see a real drop in reported pageviews after activating, your install leans on the pixel path; deactivate and you're back to baseline.

## Development

```sh
composer install
composer run lint:phpcs
```

The plugin is WPCS-clean and runs `declare(strict_types=1)` throughout.

## Changelog

See [`readme.txt`](readme.txt) for the full WordPress.org changelog.

## License

GPL v2 or later. See [LICENSE](LICENSE).

## Author

Christopher Ross — [thisismyurl.com](https://thisismyurl.com/)
