# PHP Request Service

[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

PHP library using Guzzle base to send request to any services. Good to use in microservice architecture

### Installation

[Release 3.0.0](https://github.com/not-empty/request-service-php-lib/releases/tag/3.0.0) Requires [PHP](https://php.net) 7.3

[Release 2.0.0](https://github.com/not-empty/request-service-php-lib/releases/tag/2.0.0) Requires [PHP](https://php.net) 7.2

[Release 1.0.0](https://github.com/not-empty/request-service-php-lib/releases/tag/1.0.0) Requires [PHP](https://php.net) 7.1

The recommended way to install is through [Composer](https://getcomposer.org/).

```sh
composer require not-empty/request-service-php-lib
```

### Usage

Requesting json

```php
use RequestService\Request;
$config = [
	'your-service' => [
		'url' => 'https://jsonplaceholder.typicode.com',
	],
];
$sample = new Request($config);
$response = $sample->sendRequest(
	'your-service',
	'GET',
	'todos/1'
);
var_dump($response);
```

Requesting stream

```php
use RequestService\Request;
$config = [
	'your-service' => [
		'url' => 'https://developer.marvel.com/',
		'json' => false,
	],
];
$sample = new Request($config);
$header = [
	'stream' => true,
];
$response = $sample->sendRequest(
	'your-service',
	'GET',
	'docs',
	$header
);
var_dump($response);
```

if you want an environment to run or test it, you can build and install dependences like this

```sh
docker build --build-arg PHP_VERSION=7.3.33-cli -t not-empty/request-service-php-lib:php73 -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it not-empty/request-service-php-lib:php73 bash
```

Verify if all dependencies is installed
```sh
composer install --no-dev --prefer-dist
```

and run
```sh
php sample/request-sample.php
php sample/request-image-sample.php
```

### Development

Want to contribute? Great!

The project using a simple code.
Make a change in your file and be careful with your updates!
**Any new code will only be accepted with all validations.**

To ensure that the entire project is fine:

First you need to building a correct environment to install all dependences

```sh
docker build --build-arg PHP_VERSION=7.3.33-cli -t not-empty/request-service-php-lib:php73 -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it not-empty/request-service-php-lib:php73 bash
```

Install all dependences
```sh
composer install --dev --prefer-dist
```

Run all validations
```sh
composer check
```

**Not Empty Foundation - Free codes, full minds**
