# PHP Request Service

[![Latest Version](https://img.shields.io/packagist/v/kiwfy/request-service-php?style=flat-square&label=Latest%20Version)](https://github.com/kiwfy/request-service-php/releases)
[![CI Build](https://img.shields.io/circleci/build/github/kiwfy/request-service-php/master?label=CI%20Build&token=34d8b3820b7229d742897f0a6982ced5bf6a99c8)](https://github.com/kiwfy/request-service-php)
[![codecov](https://codecov.io/gh/kiwfy/request-service-php/branch/master/graph/badge.svg?token=O47QIGFACQ&label=Codecov)](https://codecov.io/gh/kiwfy/request-service-php)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwfy/request-service-php.svg?style=flat-square&label=Total%20Downloads)](https://packagist.org/packages/kiwfy/request-service-php)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square&label=PRs%20Welcome)](http://makeapullrequest.com)

PHP library using Guzzle base to send request to any services. Good to use in microservice architecture

### Installation

[Release 2.0.0](https://github.com/kiwfy/request-service-php/releases/tag/2.0.0) Requires [PHP](https://php.net) 8.1

[Release 1.1.0](https://github.com/kiwfy/request-service-php/releases/tag/1.1.0) or earlier Requires [PHP](https://php.net) 7.1

The recommended way to install is through [Composer](https://getcomposer.org/).

```sh
composer require kiwfy/request-service-php
```

### Sample

it's a good idea to look in the sample folder to understand how it works.

First you need to building a correct environment to install dependences

```sh
docker build -t kiwfy/request-service-php -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it kiwfy/request-service-php bash
```

Verify if all dependencies is installed (if need anyelse)
```sh
composer install --no-dev --prefer-dist
```

and run
```sh
php sample/RequestSample.php
php sample/RequestImageSample.php
```

### Development

Want to contribute? Great!

The project using a simple code.
Make a change in your file and be careful with your updates!
**Any new code will only be accepted with all viladations.**

To ensure that the entire project is fine:

First you need to building a correct environment to install/update all dependences
```sh
docker build -t kiwfy/request-service-php -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it kiwfy/request-service-php bash
```

Install all dependences
```sh
composer install --dev --prefer-dist
```

Run all validations
```sh
composer check
```

**Kiwfy - Open your code, open your mind!**
