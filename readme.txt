=== WordPress.com Stats Smiley Remover ===
Contributors: thisismyurl
Tags: jetpack, stats, tracking, pixel, privacy
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 16.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Removes the WordPress.com Stats / Jetpack Stats footer tracking pixel from your site output.

== Description ==

When this plugin first shipped in 2009, the WordPress.com Stats plugin (now Jetpack Stats) emitted a visible smiley face character in the footer of every page. This plugin's original job was to remove that smiley.

Automattic retired the smiley over a decade ago, but Jetpack Stats still injects a footer tracking pixel — both as a `<noscript>` image and through the modern `Tracking_Pixel` class introduced in Jetpack 11.5. This plugin now removes that pixel instead, preserving the spirit of the original: stats can run server-side without injecting a marker into your rendered HTML.

What it does:

* Detaches the legacy `stats_footer` callback from `wp_footer` (older WP.com Stats / Jetpack).
* Detaches the modern `Automattic\Jetpack\Stats\Tracking_Pixel::add_to_footer` callback (Jetpack 11.5+).
* Does nothing when Jetpack Stats is not active — safe to leave installed regardless.

Single-file plugin. No settings, no admin UI, no enqueued assets, no third-party services.

== Installation ==

1. Install through the WordPress plugin directory or upload the plugin folder to `/wp-content/plugins/`.
2. Activate it from the **Plugins** menu in WordPress admin.
3. If Jetpack Stats is active, the tracking pixel is detached automatically.

== Frequently Asked Questions ==

= Will this break my Jetpack Stats reporting? =

In most modern Jetpack configurations, no — view tracking happens server-side or through the Jetpack site connection. The footer pixel is a fallback. If you see a drop in reported pageviews after activating this plugin, you may rely on the pixel-based path, in which case you can deactivate it.

= Does this work without Jetpack Stats installed? =

Yes — the plugin detects the absence of Jetpack and is a safe no-op. You can leave it installed even on sites that don't use Jetpack.

= Why is this plugin still called "Smiley Remover" if the smiley is gone? =

The slug and name were preserved for continuity with the original wp.org listing and existing installs. The plugin's modern job is the lineal descendant of the original: removing footer artifacts that Jetpack Stats injects into your output.

== Changelog ==

= 16.0.0 =
* Full rewrite from scratch for 2026.
* Now removes the modern Jetpack Stats footer tracking pixel (the smiley itself was retired by Automattic over a decade ago).
* Modern PHP (`declare(strict_types=1)`, namespaces, `Requires PHP: 7.4`).
* Removed the bundled "common framework," donate prompt, and admin settings page.
* Removed the CSS-injection approach used in earlier versions — modern Jetpack does not emit a visible smiley, so the CSS rule is no longer relevant.
* Single-file plugin, WPCS-clean.

= 14.12.01 =
* Added language file support, German and French Canadian translations.
* Moved menu item handling, fixed typos.

= 14.11 =
* Made code WP 4.0.1 ready, converted to OOP, used `wp_enqueue_scripts()` hook.

= 4.1.0 =
* Added additional hide CSS.

= 1.0.0 =
* Official release as a full, stable plugin.

== Upgrade Notice ==

= 16.0.0 =
Full rewrite. The plugin now removes the modern Jetpack Stats footer tracking pixel rather than the long-retired smiley character. No settings or admin UI. Safe to leave installed even if Jetpack Stats is not active.
