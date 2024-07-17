# XClient

Wrapper for Guzzle

## Support us

## Installation

You can install the package via composer:

```bash
composer require jooservices/xclient
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="xclient-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="xclient-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="xclient-views"
```

## Usage

```php
$xClient = new JOOservices\XClient();
echo $xClient->echoPhrase('Hello, JOOservices!');
```

## Testing

```bash
composer test
```

## Changelog

## Contributing

## Security Vulnerabilities

## Credits

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
