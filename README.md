# WordPress.com Stats Smiley Remover

[![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-blue)](https://wordpress.org/plugins/wordpresscom-stats-smiley-remover/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)

Single-file WordPress plugin that detaches the WordPress.com Stats / Jetpack Stats footer tracking pixel from your rendered HTML.

## Why the name?

I shipped this plugin in 2009 to hide a visible smiley character that the WordPress.com Stats plugin injected into the footer of every page. The original used a CSS rule. Automattic retired the smiley around 2012, so that rule stopped doing anything useful a long time ago.

Jetpack Stats today still injects a footer tracking pixel — through the legacy `stats_footer` global function on pre-11.5 stacks, and through `Automattic\Jetpack\Stats\Tracking_Pixel::add_amp_pixel` on Jetpack 11.5+ for AMP and Web Stories renders. Same shape of problem, different decade. I rewrote the plugin to do for Jetpack Stats what the original did for WP.com Stats.

The slug and listing name are preserved for continuity with the original wp.org page and the inbound links it has accumulated since 2009.

## What it does

- Detaches the legacy `stats_footer` callback from `wp_footer` (pre-11.5 Jetpack and WP.com Stats classic).
- Detaches `Automattic\Jetpack\Stats\Tracking_Pixel::add_amp_pixel` from `wp_footer` and `web_stories_print_analytics` (Jetpack 11.5+).
- Leaves the JavaScript stats script alone — modern non-AMP tracking continues to work.
- Safe no-op when Jetpack Stats is not installed or active.

It's a small, single-purpose plugin. No settings page, no admin chrome, no tracking. Activate it and it works. Deactivate it and it leaves no trace.

Originally published in 2009, rewritten in 2026 for current WordPress and current Jetpack Stats.

## Requirements

- WordPress 6.4 or later.
- PHP 7.4 or later.
- Jetpack Stats (optional — the plugin is a no-op without it).

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

See [releases](../../releases) or [readme.txt](readme.txt).

---

## Support and donations

I build these tools because WordPress sites in the wild keep hitting the same problems, and a small, focused plugin is usually the right fix. They're free to use, with no tracking and no ads.

If one of them saves you time, here are the genuine ways to help:

- **Sponsor the work.** [GitHub Sponsors](https://github.com/sponsors/thisismyurl) is the simplest way, and the Sponsor button at the top of this repo lists it alongside Bitcoin, Dogecoin, PayPal, and Interac e-transfer. Any amount helps, and none of it is expected.
- **Contribute code or ideas.** A pull request, a bug report, or a tested edge case is worth as much as a donation. See [CONTRIBUTING.md](CONTRIBUTING.md) to get started.
- **Share it.** A note on [WordPress.org](https://profiles.wordpress.org/thisismyurl/), [GitHub](https://github.com/thisismyurl), or [LinkedIn](https://linkedin.com/in/thisismyurl) helps other people find work that might save them the same afternoon.

### Report issues and questions

- **Found a bug or want a feature?** Open an issue on the [Issues](../../issues) tab. Include your WordPress and PHP versions and the steps to reproduce it.
- **Have a question?** Start a thread on the [Discussions](../../discussions) tab.

### Contributing code

Code contributions are welcome. The short version:

1. Fork the repository and clone your fork.
2. Create a branch with a clear name, like `feature/short-descriptive-name`.
3. Make your change and test it against the edge cases.
4. Run the coding-standards check before you open the pull request.
5. Open a pull request that explains what changed and why.

The full workflow and standards live in [CONTRIBUTING.md](CONTRIBUTING.md). Contributing is never required, but it is always appreciated.

## About This Is My URL

This plugin is built and maintained by [This Is My URL](https://thisismyurl.com/), the WordPress development and technical SEO practice of Christopher Ross. I help teams build WordPress sites that stay secure, fast, and maintainable, and I write small, focused plugins like this one for the problems those sites keep running into.

### My background

- On the web since 1996, and in WordPress since 2007
- WordPress.org plugin developer with 19 plugins published since 2009
- Technical SEO practitioner focused on performance, security, and search visibility
- Lead instructor and curriculum architect at the M.L. Campbell Training Center, the Sherwin-Williams® international training facility for its industrial wood division

### Ways to connect

- **Website:** [thisismyurl.com](https://thisismyurl.com/)
- **WordPress.org:** [profiles.wordpress.org/thisismyurl](https://profiles.wordpress.org/thisismyurl/)
- **GitHub:** [github.com/thisismyurl](https://github.com/thisismyurl)
- **LinkedIn:** [linkedin.com/in/thisismyurl](https://linkedin.com/in/thisismyurl)

## Contributors

- **Christopher Ross** ([@thisismyurl](https://github.com/thisismyurl)) — author and maintainer
- Thanks to everyone who has reported issues, tested edge cases, and contributed code

## License

GPL-2.0-or-later — see [LICENSE](LICENSE) or [gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html).

---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*
