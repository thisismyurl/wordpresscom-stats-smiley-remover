# WordPress.com Stats Smiley Remover

Single-file WordPress plugin that removes the WordPress.com Stats / Jetpack Stats footer tracking pixel from your site output.

[![WordPress.org](https://img.shields.io/wordpress/plugin/installs/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/)
[![Rating](https://img.shields.io/wordpress/plugin/r/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/#reviews)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL_v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)

---

## Why the name?

When this plugin first shipped in 2009, the WordPress.com Stats plugin emitted a visible **smiley character** in the footer of every page. The plugin removed it with a CSS rule. Automattic retired the smiley over a decade ago, but Jetpack Stats still injects a footer tracking pixel — both as a `<noscript>` image and through the modern `Tracking_Pixel` class introduced in Jetpack 11.5.

The plugin's modern job is the lineal descendant of the original: removing footer artifacts that Jetpack Stats injects into your HTML output. The slug and name are preserved for continuity with the original wp.org listing and existing installs.

## What it does

- Detaches the legacy `stats_footer` callback from `wp_footer` (older WP.com Stats and Jetpack releases).
- Detaches the modern `Automattic\Jetpack\Stats\Tracking_Pixel::add_to_footer` callback (Jetpack 11.5+).
- Safe no-op when Jetpack Stats is not installed or active.
- Single file. No settings, no admin UI, no enqueued assets, no third-party services.

## Requirements

- WordPress 6.4 or later.
- PHP 7.4 or later.
- Jetpack Stats (optional — plugin is a no-op without it).

## Installation

1. Install through the [WordPress plugin directory](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/) or upload the plugin folder to `wp-content/plugins/`.
2. Activate it from **Plugins** in WordPress admin.
3. If Jetpack Stats is active, the footer tracking pixel is detached automatically.

## How it works

```php
// In wordpresscom-stats-smiley-remover.php
add_action( 'wp_loaded', __NAMESPACE__ . '\\detach_pixel', PHP_INT_MAX );

function detach_pixel(): void {
    remove_action( 'wp_footer', 'stats_footer', 101 );

    if ( class_exists( 'Automattic\\Jetpack\\Stats\\Tracking_Pixel' ) ) {
        remove_action(
            'wp_footer',
            [ 'Automattic\\Jetpack\\Stats\\Tracking_Pixel', 'add_to_footer' ],
            101
        );
    }
}
```

`remove_action()` fails silently when the hook callback isn't registered, so we don't gate on `function_exists()` for the legacy path — we just call the removals once.

## Will this break my Jetpack Stats reporting?

In most modern Jetpack configurations, no — view tracking happens server-side or through the Jetpack site connection. The footer pixel is a fallback. If you see a drop in reported pageviews after activating this plugin, you likely rely on the pixel-based path; in that case, deactivate the plugin.

## Development

```sh
composer install
composer run lint:phpcs
```

## Changelog

See [`readme.txt`](readme.txt) for the full WordPress.org changelog.

## License

GPL v2 or later. See [LICENSE](LICENSE).

## Author

Christopher Ross — [thisismyurl.com](https://thisismyurl.com/)
