# PHP Credential Manager

PHP library for search credential in redis with Predis.

### Tech

Promove a simplest way to return credential token or generate if declare a custom method using:

* [Predis] - Flexible and feature-complete Redis client for PHP.


### Installation

Requires [PHP](https://php.net) 7.1.

The recommended way to install is through Composer.

```sh
composer require espositovitor/credential-manager-php
```

### Samples

it's a good idea to look in the sample folder to understand how it works.
Run all files with PHP to see many examples.

```sh
php sample/CredentialSample.php
php sample/CredentialRedisConfigSample.php
php sample/CredentialGenerateTokenSample.php
```

### Development

Want to contribute? Great!

The project using a simple code in PHP.
Make a change in your file and be careful with your updates!
**Any new code will only be accepted with test.**

To ensure that the entire project is tested:

First install the dependences
```sh
$ composer install
```

Second run tests
```sh
$ vendor/bin/phpunit
```

**Free Software, Hell Yeah!**
