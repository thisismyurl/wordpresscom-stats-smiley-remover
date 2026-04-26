# WordPress.com Stats Smiley Remover

Removes the smiley face character that the WordPress.com Stats / JetPack stats module appends to the footer of every WordPress page. Adds a single line of W3C-compliant CSS that hides the character without disabling stats collection.

[![WordPress.org](https://img.shields.io/wordpress/plugin/installs/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/)
[![Rating](https://img.shields.io/wordpress/plugin/r/wordpresscom-stats-smiley-remover.svg)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/#reviews)

## Why this exists

When WordPress.com Stats (later folded into JetPack) is active, it injects a smiley face glyph into the page footer as a visible signal that the stats module is reporting. The glyph is small but unwanted on most production designs — it disrupts custom footers and looks like a rendering bug to clients.

This plugin hides the glyph with CSS without touching the stats module itself. Stats collection continues to work; the visual artifact disappears.

## Features

- Hides the WordPress.com Stats smiley footer character.
- Pure CSS approach — no JavaScript, no DOM manipulation.
- Stats collection unaffected.
- Multilingual support (English, German, French).

## Requirements

- WordPress 3.2.0 or later (tested through 4.1; works on modern WP).
- PHP 5.3+.
- WordPress.com Stats / JetPack stats active (otherwise nothing to hide).

## Installation

1. Upload the plugin folder to `wp-content/plugins/` or install via the WordPress.org directory.
2. Activate from **Plugins** in WordPress admin.
3. Reload your site footer — the smiley is gone.

## Status

Maintenance mode. Active installs: ~300.
The plugin is intentionally minimal and does not need active development.

For active development of WordPress + SEO tooling, see [thisismyurl.com](https://thisismyurl.com/).

## License

GPL v2 or later.

## Contributors

- Christopher Ross — original author
- Meagan Hanes — co-maintainer

