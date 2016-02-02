# Import fragments from an excel file

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/fragment-importer.svg?style=flat-square)](https://packagist.org/packages/spatie/fragment-importer)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/fragment-importer/master.svg?style=flat-square)](https://travis-ci.org/spatie/fragment-importer)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/xxxxxxxxx.svg?style=flat-square)](https://insight.sensiolabs.com/projects/xxxxxxxxx)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/fragment-importer.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/fragment-importer)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/fragment-importer.svg?style=flat-square)](https://packagist.org/packages/spatie/fragment-importer)

This Blender specific package provides some classes and commands to easily import fragments
using an excel file. An exporter is included too.

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Install

This package is custom built for [Spatie](https://spatie.be) projects and is therefore not registered on packagist. 
In order to install it via composer you must specify this extra repository in `composer.json`:

```json
"repositories": [ { "type": "composer", "url": "https://satis.spatie.be/" } ]
```

You can install the package via composer:
``` bash
$ composer require spatie/fragment-importer
```

## Usage

Fragments not yet present in the database can be imported using:
``` console
php artisan fragment:import
```

If you want to update the existing ones as well, run this command:
``` console
php artisan fragment:import --update
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
