# WordPress.com Stats Smiley Remover

Single-file WordPress plugin that detaches the WordPress.com Stats / Jetpack Stats footer tracking pixel from your rendered HTML.

[![WordPress.org](https://img.shields.io/wordpress/plugin/installs/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/)
[![Rating](https://img.shields.io/wordpress/plugin/r/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/#reviews)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL_v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)

---

## Why the name?

I shipped this plugin in 2009 to hide a visible smiley character that the WordPress.com Stats plugin injected into the footer of every page. The original used a CSS rule. Automattic retired the smiley around 2012, so that rule stopped doing anything useful a long time ago.

Jetpack Stats today still injects a footer tracking pixel — through the legacy `stats_footer` global function on pre-11.5 stacks, and through `Automattic\Jetpack\Stats\Tracking_Pixel::add_amp_pixel` on Jetpack 11.5+ for AMP and Web Stories renders. Same shape of problem, different decade. I rewrote the plugin to do for Jetpack Stats what the original did for WP.com Stats.

The slug and listing name are preserved for continuity with the original wp.org page and the inbound links it has accumulated since 2009.

## What it does

- Detaches the legacy `stats_footer` callback from `wp_footer` (pre-11.5 Jetpack and WP.com Stats classic).
- Detaches `Automattic\Jetpack\Stats\Tracking_Pixel::add_amp_pixel` from `wp_footer` and `web_stories_print_analytics` (Jetpack 11.5+).
- Leaves the JavaScript stats script alone — modern non-AMP tracking continues to work.
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

Modern Jetpack registers its `wp_footer` callback from inside `Automattic\Jetpack\Stats\Main::template_redirect()`, which itself runs at `template_redirect` priority 1. So the only safe place to remove that hook is **after** Jetpack's `template_redirect` callback has fired — hooking on `wp_loaded` (or any earlier action) runs too soon and the removal silently no-ops.

```php
function bootstrap(): void {
    add_action( 'template_redirect', __NAMESPACE__ . '\\detach_pixel', PHP_INT_MAX );
}

function detach_pixel(): void {
    remove_action( 'wp_footer', 'stats_footer', 101 );

    if ( ! class_exists( 'Automattic\\Jetpack\\Stats\\Tracking_Pixel' ) ) {
        return;
    }

    $callback = [ 'Automattic\\Jetpack\\Stats\\Tracking_Pixel', 'add_amp_pixel' ];

    remove_action( 'wp_footer', $callback, 101 );
    remove_action( 'web_stories_print_analytics', $callback, 101 );
}
```

`remove_action()` returns silently when the hook callback isn't registered, so the legacy path doesn't need a `function_exists()` guard. The modern path is class-gated to skip the array construction when Jetpack isn't loaded — small thing, but the difference between "lazy senior code" and "lazy junior code" lives in details like that.

## Will this break my Jetpack Stats reporting?

On non-AMP pages, no. Modern Jetpack tracks via a JavaScript file enqueued through `wp_enqueue_scripts`, and this plugin doesn't touch that path. On AMP-rendered pages and Web Stories, the footer pixel is the only tracking surface — detaching it does mean those views stop being counted. If your traffic is mostly AMP, deactivate the plugin or scope it to non-AMP requests.

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
