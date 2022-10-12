# Autopilot Terminus Plugin

[![CircleCI](https://circleci.com/gh/pantheon-systems/terminus-plugin-example.svg?style=shield)](https://circleci.com/gh/pantheon-systems/terminus-autopilot-plugin)
[![Terminus v3.x Compatible](https://img.shields.io/badge/terminus-3.x-green.svg)](https://github.com/pantheon-systems/terminus-autopilot-plugin)

Terminus plugin for controlling Autopilot.


## Configuration

These commands require no configuration

## Usage
* No functioning commands currently implemented.

## Installation

To install this plugin using Terminus 3:
```
terminus self:plugin:install terminus-autopilot-plugin
```

On older versions of Terminus:
```
mkdir -p ~/.terminus/plugins
curl https://github.com/pantheon-systems/terminus-plugin-example/archive/2.x.tar.gz -L | tar -C ~/.terminus/plugins -xvz
```

## Testing
This example project includes four testing targets:

* `composer lint`: Syntax-check all php source files.
* `composer cs`: Code-style check.
* `composer unit`: Run unit tests with phpunit
  * No Unit tests currently implemented.
* `composer functional`: Run functional test with bats
  * * No Functional tests currently implemented.
To run all tests together, use `composer test`.

Note that prior to running the tests, you should first run:
* `composer install`
* `composer install-tools`

## Help
Run `terminus help autopilot` for help.
