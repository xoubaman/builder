# Changelog

All notable changes to this project are documented here. The project follows [Semantic Versioning](https://semver.org/).

## 3.0.0

### Breaking changes

* PHP 8.3 or newer is required.
* `roave/better-reflection` ^6.69 and `symfony/console` ^6.4, ^7.0 or ^8.0 are required.
* `ext-mbstring` is now a declared requirement.
* `Builder::build()` and `Builder::cloneLast()` declare a `mixed` return type. Child builders that override them must declare a return type: `mixed`, or the class being built.
* The `$base`, `$current` and `$lastSetup` properties are typed as `array`. Child builders that redeclare them must type them as `array` too.
* `Builder::__call()` declares `string $name, array $arguments`. Child builders that override it must use the same signature.
* The protected helpers `addToCurrent()`, `removeFromCurrent()` and `replaceCurrentSetup()` return `static`.

### Fixed

* `vendor/bin/builder build` returned exit code `1` on success. It now returns `0`.
* The CLI works with `symfony/console` 8, where `Application::add()` was removed.

### Changed

* Code modernized to PHP 8.3: constructor property promotion, readonly properties, arrow functions.
* The generator supports classes without a constructor.
* Mago replaces PHP_CodeSniffer and PHPStan for linting and formatting.
* Tests run on GitHub Actions against PHP 8.3, 8.4 and 8.5, with the highest and lowest dependency versions.
* `composer.lock` is no longer committed, as recommended for libraries.

## 2.0.2 and earlier

See the [GitHub releases](https://github.com/xoubaman/builder/releases) and tags. The 1.x and 2.x lines are no longer maintained.
