=== WordPress.com Stats Smiley Remover ===
Contributors: thisismyurl
Tags: jetpack, stats, tracking, pixel, privacy
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 16.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Detaches the WordPress.com Stats / Jetpack Stats footer tracking pixel from your rendered HTML.

== Description ==

I shipped the first version of this plugin in 2009 to hide a visible smiley character that WordPress.com Stats injected into the footer of every page. Automattic retired the smiley around 2012, so the original CSS-hiding trick stopped being useful a long time ago.

Jetpack Stats today still injects a tracking pixel into your footer — through the legacy `stats_footer` global function on pre-11.5 stacks, and through `Automattic\Jetpack\Stats\Tracking_Pixel::add_amp_pixel` on Jetpack 11.5+ (AMP and Web Stories renders). Same shape of problem, different decade. I rewrote the plugin to do for Jetpack Stats what the original did for WP.com Stats: detach the footer artifact and leave the rest of your site alone.

The slug and name are kept for continuity with the original wp.org listing. If that's confusing, the FAQ below addresses it directly.

What it does:

* Detaches the legacy `stats_footer` callback from `wp_footer` (pre-11.5 Jetpack and WP.com Stats).
* Detaches `Automattic\Jetpack\Stats\Tracking_Pixel::add_amp_pixel` from `wp_footer` and `web_stories_print_analytics` (Jetpack 11.5 and later).
* Leaves the JavaScript stats script alone — modern non-AMP tracking continues to work.
* Safe no-op when Jetpack Stats is not installed or active.

Small, single-purpose plugin. No settings page, no admin chrome, no tracking. Activate it and it works. Deactivate it and it leaves no trace.

Originally published 2009, rewritten 2026 for current WordPress and current Jetpack Stats.

= About the author =

Built and maintained by Christopher Ross — 25 years working with WordPress, currently running a senior-dev consulting practice at This Is My URL. More plugins, writing, and case studies at [thisismyurl.com](https://thisismyurl.com/).

== Installation ==

1. Install through the WordPress plugin directory or upload the plugin folder to `/wp-content/plugins/`.
2. Activate it from the **Plugins** menu in WordPress admin.
3. If Jetpack Stats is active, the footer tracking pixel is detached automatically. If it isn't, the plugin does nothing.

== Frequently Asked Questions ==

= Will this break my Jetpack Stats reporting? =

On non-AMP pages, no. Modern Jetpack tracks via a JavaScript file enqueued through `wp_enqueue_scripts`, and this plugin doesn't touch that path. On AMP-rendered pages and Web Stories, the footer pixel is the only tracking surface, so detaching it does mean those views stop being counted. If your traffic is mostly AMP, deactivate the plugin or scope it conditionally.

= Why is the plugin still called "Smiley Remover" if the smiley is gone? =

Because the slug and listing have lived at this URL since 2009 and changing them would break installs and break inbound links from old blog posts and Stack Overflow answers. The honest answer is that the plugin's job today is the lineal descendant of the original — it removes a footer artifact that the WP.com Stats / Jetpack Stats lineage has been injecting in one form or another for fifteen-plus years. The artifact changed. The intent didn't.

= Does this work without Jetpack installed? =

Yes. The plugin checks for the modern `Tracking_Pixel` class before touching it, and `remove_action()` is a safe no-op when the legacy callback isn't registered. You can leave it installed on sites that don't run Jetpack at all.

= Why not just turn off Jetpack Stats? =

If you don't want any of Jetpack Stats, that's the cleaner answer. This plugin is for sites that want the server-side reporting Jetpack Stats provides without the footer pixel sitting in their rendered HTML. Two different use cases.

== Changelog ==

= 16.0.0 =
* Full rewrite from scratch for 2026.
* Now detaches the modern Jetpack Stats footer tracking pixel — the legacy `stats_footer` callback and `Automattic\Jetpack\Stats\Tracking_Pixel::add_amp_pixel` (Jetpack 11.5+, AMP and Web Stories paths).
* Removed the CSS-hiding approach used in earlier versions. The smiley it targeted was retired by Automattic over a decade ago, so the rule no longer applies to anything.
* Modern PHP (`declare(strict_types=1)`, namespaces, `Requires PHP: 7.4`).
* Removed the bundled "common framework," donate prompt, and admin settings page.
* Single-file plugin, WPCS-clean, no enqueued assets.

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
Full rewrite. The plugin now detaches the Jetpack Stats footer tracking pixel in PHP instead of hiding the long-retired smiley character with CSS. Same intent, current target. No settings or admin UI. Safe to leave installed even when Jetpack Stats is not active.
