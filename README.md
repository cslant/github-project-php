# GitHub Project PHP

<p align="center">
<a href="#"><img src="https://img.shields.io/github/license/cslant/github-project-php.svg?style=flat-square" alt="License"></a>
<a href="https://github.com/cslant/github-project-php/releases"><img src="https://img.shields.io/github/release/cslant/github-project-php.svg?style=flat-square" alt="Latest Version"></a>
<a href="https://packagist.org/packages/cslant/github-project-php"><img src="https://img.shields.io/packagist/dt/cslant/github-project-php.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://github.com/cslant/github-project-php/actions/workflows/setup_test.yml"><img src="https://img.shields.io/github/actions/workflow/status/cslant/github-project-php/setup_test.yml?label=tests&branch=main" alt="Test Status"></a>
<a href="https://github.com/cslant/github-project-php/actions/workflows/php-cs-fixer.yml"><img src="https://img.shields.io/github/actions/workflow/status/cslant/github-project-php/php-cs-fixer.yml?label=code%20style&branch=main" alt="Code Style Status"></a>
</p>

## Introduction

GitHub Project PHP is a Laravel package that helps you manage your GitHub Projects (V2) in PHP.

It provides a simple webhook system to receive GitHub project events and automatically generate activity comments on issues and pull requests.

## Features

- **Webhook Integration**: Receive and process GitHub Projects V2 webhook events
- **Queue Support**: Process webhook events asynchronously for better performance
- **API Endpoint**: Generate comment messages via REST API
- **12+ Field Type Templates**: Built-in templates for all GitHub Project field types
- **Customizable Templates**: Publish and customize Blade templates
- **GitHub GraphQL API**: Direct integration with GitHub's GraphQL API

## Requirements

- PHP ^8.4
- Laravel ^11.0|^12.0
- [Composer](https://getcomposer.org/)

## Installation

```bash
composer require cslant/github-project-php
```

## Available Field Type Templates

| Field Type      | Description                                |
|-----------------|--------------------------------------------|
| `text`          | Simple text fields                         |
| `number`        | Numeric fields                             |
| `date`          | Date fields (Y-m-d format)                 |
| `single_select` | Single-select dropdowns with color support |
| `multi_select`  | Multi-select fields                        |
| `checkbox`      | Boolean/toggle fields                      |
| `textarea`      | Long text content with diff view           |
| `iteration`     | Iteration/sprint fields                    |
| `labels`        | Label/tag fields                           |
| `assignees`     | User assignment fields                     |
| `milestone`     | Milestone tracking                         |
| `unsupported`   | Fallback for unknown field types           |

## Usage

See the [GitHub Project PHP Documentation](https://docs.cslant.com/github-project-php/advanced/templates) for detailed usage instructions.

### Customizing Templates

```bash
php artisan vendor:publish --provider="CSlant\GitHubProject\GithubProjectServiceProvider" --tag="views"
```

This copies templates to `resources/views/vendor/github-project/md/field_types/`.

### Template Variables

All field type templates receive:

- `$fieldName` - Display name of the field
- `$fieldType` - Type of the field
- `$fromValue` - Previous value
- `$toValue` - New value
- `$fieldData` - Raw webhook data

### API Endpoint

Generate comment messages via API:

```
POST /github-project/generate-comment
Content-Type: application/json

{
  "payload": { ... }
}
```

### Queue Configuration

Enable queue processing in your config:

```php
// config/github-project.php
'is_queue_enabled' => true,
```

## Documentation

Full documentation available at [docs.cslant.com/github-project-php](https://docs.cslant.com/github-project-php)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
