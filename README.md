# Terminus Autopilot Plugin

[![CircleCI](https://circleci.com/gh/pantheon-systems/terminus-autopilot-plugin.svg?style=shield)](https://circleci.com/gh/pantheon-systems/terminus-autopilot-plugin)

[![Terminus v3.x Compatible](https://img.shields.io/badge/terminus-3.x-green.svg)](https://github.com/pantheon-systems/terminus-autopilot-plugin)

Terminus plugin for controlling Autopilot.

## Configuration

These commands require no configuration.

## Commands

* `site:autopilot:activate {SITE_NAME|SITE_ID}`

* `site:autopilot:deactivate {SITE_NAME|SITE_ID}`

   Activate/Deactivate Autopilot for a given site name or id.

* `site:autopilot:deployment-destination {SITE_NAME|SITE_ID} {{ENV}}`

   Get or set Autopilot deployment destination environment. Env is optional.
   Use command without an ENV to get and add the ENV to set.

* `site:autopilot:env-sync`

   Get Autopilot environment syncing setting. Returns
   true if enabled, false if not. Env syncing will sync
   the target autopilot environment to the live environment
   efore applying updates in an autopilot cycle.

* `site:autopilot:env-sync:enable`

* `site:autopilot:env-sync:disable`

   Explicitly set syncing setting to `enabled/disabled`.
   Env syncing will sync the target autopilot environment 
   to the live environment before applying updates in an autopilot cycle.   

* `site:autopilot:frequency`

   Get or set Autopilot run frequency with which autopilot update
   cycles are run. Valid options are: manual, monthly, weekly, daily.


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
