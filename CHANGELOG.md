# Changelog

Here you can see the full list of changes between each GitHub Project PHP package release.

## v2.0.0 - 2026-03-03

### 🚀 Major Release - PHP 8.4+ & Modern Architecture

### ✨ New Features

- **API Endpoint**: New `GenerateCommentAction` to generate comment messages via REST API
- **Route Names**: All routes now have named routes for better maintainability

### 🔧 Improvements

- **PHP Version**: Upgraded to PHP ^8.4
- **Strict Types**: Added `declare(strict_types=1)` to all PHP files
- **Constructor Property Promotion**: All classes use modern PHP 8.4 syntax with `readonly`
- **Strict Comparisons**: All loose `==`/`!=` replaced with strict `===`/`!==`
- **Removed `empty()`**: Replaced with explicit null/empty-string checks
- **Dependency Injection**: Improved DI in `ProcessAggregatedEvents` job
- **Simplified ServiceProvider**: Using `mergeConfigFrom()` instead of manual scandir loop

### 🐛 Bug Fixes

- Fixed ServiceProvider namespace (`CSlant\GithubProject` → `CSlant\GitHubProject`)
- Fixed double `validatePayloadForComment()` call in `WebhookService`
- Fixed lang file copy-paste errors
- Removed redundant `Cache::forget()` after `Cache::pull()`
- Removed dead `array_map` identity function in `aggregateMessages()`

### 🔒 Security

- Removed stack trace/file/line leak in `GenerateCommentAction` error response

### 🧹 Code Cleanup

- Removed empty `registerCommands()` method
- Removed empty `Constants/` and `Http/` directories
- Fixed `provides()` return type annotation
- Cleaned up PHPStan baseline (removed overly broad wildcards)
- Net: -133 lines removed

### 💥 Breaking Changes

- PHP `^8.4` now required
- Namespace corrected from `CSlant\GithubProject` to `CSlant\GitHubProject`

### 📦 Dependencies Updated

- `orchestra/testbench`: ^10.0
- `pestphp/pest`: ^4.0
- `nunomaduro/larastan`: ^3.9
- `phpstan/phpstan`: ^2.0

**Full Changelog**: https://github.com/cslant/github-project-php/compare/v1.2.0...v2.0.0

## v1.2.0 - 2025-07-06

### What's Changed

* Add api to get the comment message with payload.
* chore(deps): bump dependabot/fetch-metadata from 2.3.0 to 2.4.0 by @dependabot in https://github.com/cslant/github-project-php/pull/5
* chore(deps): bump stefanzweifel/git-auto-commit-action from 5 to 6 by @dependabot in https://github.com/cslant/github-project-php/pull/6

### New Contributors

* @dependabot made their first contribution in https://github.com/cslant/github-project-php/pull/5

**Full Changelog**: https://github.com/cslant/github-project-php/compare/v1.1.0...v1.2.0

## v1.1.0 - 2025-02-20

### What's Changed

* Queue event by @tanhongit in https://github.com/cslant/github-project-php/pull/3

### New Contributors

* @tanhongit made their first contribution in https://github.com/cslant/github-project-php/pull/3

**Full Changelog**: https://github.com/cslant/github-project-php/compare/v1.0.0...v1.1.0

## v1.0.0 - 2025-02-14

### What's Changed

- Handle comments for all field types in GitHub project V2.
- Create an enable comment flag for the Status field.
- Format color field values.

**Full Changelog**: https://github.com/cslant/github-project-php/compare/v0.1.0...v1.0.0

## v0.1.0 - 2025-03-12

- Experimental release
