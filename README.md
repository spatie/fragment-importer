# Import fragments from an excel file

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/9012cf42-8d1a-4649-b5ab-b96db48eed21.svg?style=flat-square)](https://insight.sensiolabs.com/projects/9012cf42-8d1a-4649-b5ab-b96db48eed21)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/fragment-importer.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie-custom/fragment-importer)
[![StyleCI](https://styleci.io/repos/50928093/shield?branch=master)](https://styleci.io/repos/50928093)

This Blender specific package provides some classes and commands to easily import fragments
using an excel file. An exporter is included too.

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/fragment-importer.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/fragment-importer)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

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

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
