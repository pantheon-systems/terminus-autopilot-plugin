# Terminus Autopilot Plugin

[![CircleCI](https://circleci.com/gh/pantheon-systems/terminus-autopilot-plugin.svg?style=shield)](https://circleci.com/gh/pantheon-systems/terminus-autopilot-plugin)
[![Terminus v3.x Compatible](https://img.shields.io/badge/terminus-3.x-green.svg)](https://github.com/pantheon-systems/terminus-autopilot-plugin)

Terminus plugin for controlling Autopilot.

## Configuration

These commands require no configuration.

## Commands
* `site:autopilot:env-sync`
* `site:autopilot:env-sync:enable`
* `site:autopilot:env-sync:disable`
* `site:autopilot:frequency`
* `site:autopilot:deployment-destination`

## Installation

To install this plugin using Terminus 3:
```
terminus self:plugin:install terminus-autopilot-plugin
```

## Testing
This project includes four testing targets:

* `composer lint`: Syntax-check all php source files.
* `composer cs`: Code-style check.
* `composer functional`: Run functional tests.
To run all checks, use `composer test`.

Note that prior to running the tests, you should first run:
* `composer install`
