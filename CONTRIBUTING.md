# Contributing

Pull requests welcome.

## Setup

```sh
composer install
```

## Standards

The plugin is held to **WordPress-Extra** PHPCS rules with PHPCompatibilityWP for PHP 7.4+. Run linting before submitting:

```sh
composer run lint:phpcs
```

Auto-fix what's safely fixable:

```sh
composer run fix:phpcs
```

CI runs the same checks on every push and pull request — see [`.github/workflows/lint.yml`](.github/workflows/lint.yml).

## Workflow

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/your-change`).
3. Commit your changes with a clear message — explain *why*, not just what.
4. Push to your fork and open a Pull Request against `main`.

For larger changes, open an issue first to discuss scope.

## Scope

This plugin is intentionally tiny. Bug reports and security fixes are always welcome. New features will be evaluated against the plugin's stated purpose — single-purpose plugins age better than feature-creep ones.
