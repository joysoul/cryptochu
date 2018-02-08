# cryptochu

[![Build Status](https://travis-ci.com/epels/cryptochu.svg?token=fFCsEza59TasaQuy6qDV&branch=master)](https://travis-ci.com/epels/cryptochu)

### Coding style
We follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) style guide.

### Installation
Clone this repository and run `composer install` from its root directory.

Make sure the `intl` PHP extension is [installed](http://php.net/manual/en/intl.installation.php). If the test suite fails, this is likely the problem.
On MacOS, it is easily installed through Homebrew (e.g. `brew install php72-intl` when using PHP 7.2).

### Tests
To run tests with default settings, simply run the following command. It will look in `phpunit.xml`.
```
vendor/bin/phpunit
```
