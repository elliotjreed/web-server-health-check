[![Build Status](https://travis-ci.org/elliotjreed/web-server-health-check.svg?branch=master)](https://travis-ci.org/elliotjreed/web-server-health-check)[![Coverage Status](https://coveralls.io/repos/github/elliotjreed/web-server-health-check/badge.svg?branch=master)](https://coveralls.io/github/elliotjreed/web-server-health-check?branch=master)

# Web Server Health Checker

An application to check the response of URLs from a provided XML sitemap URL.


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.


### Prerequisites

This package requires:
 - PHP 7.1+
 - [Composer](https://getcomposer.org/)


### Installing

To install the required dependencies, run:

```bash
composer install
```


## Running the tests

To run the unit tests and code sniffer checks, run:

```bash
composer run-script test
```

To run the unit tests, run:

```bash
composer run-script phpunit
```

To run the code sniffer, run:

```bash
composer run-script phpcs
```


## Usage

```bash
php bin/check.php https://www.example.net/sitemap.xml
```


## Built With

* [PHP](https://secure.php.net/)
* [PHPUnit](https://phpunit.de/) - Unit Testing
* [Composer](https://getcomposer.org/) - Dependency Management
