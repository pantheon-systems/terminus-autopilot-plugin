# Terminus Autopilot Plugin

## Autopilot

Pantheon's Autopilot:

- Automatically detects when new updates are available
- Performs the updates in an isolated [Multidev](https://pantheon.io/docs/guides/multidev) environment
- Tests the updates with automated visual regression testing (VRT)
- Optionally deploys the updates

You can perform comprehensive Autopilot functions through your [Dashboard](https://dashbord.pantheon.io). Refer to the [Autopilot guide](https://pantheon.io/docs/guides/autopilot) for more information. 

## Site Compatibility

Review the [Autopilot Site Compatibility](https://pantheon.io/docs/guides/autopilot#autopilot-site-compatibility) documentation on Pantheon to ensure that your site is compatible with Autopilot.

## Plugin Functionality

### Terminus Autopilot Plugin Requirements

Autopilot requires the following:

- A site with Autopilot available
- Terminus 3 or 4

### Terminus Autopilot Plugin Functionality

The Terminus Autopilot plugin does not currently provide the following functionality:

- Selection of specific modules, themes, or Custom Upstreams for updates
- Management of excluded updates

## Installation

Run the command below to install the Terminus Autopilot plugin.

`terminus self:plugin:install terminus-autopilot-plugin`

## Terminus Autopilot Commands

This section provides currently supported commands for the Terminus Autopilot plugin.

###  Activate or Deactivate Autopilot

You can activate for deactivate Autopilot for a specific site name or ID.

To **activate** a site:

`site:autopilot:activate {SITE_NAME|SITE_ID}`

To **deactivate** a site:

`site:autopilot:deactivate {SITE_NAME|SITE_ID}`

### Get the Autopilot Destination Environment

You can use the command below to get the destination environment in which Autopilot is currently running. 

`site:autopilot:deployment-destination {SITE_NAME|SITE_ID}` 

### Set the Autopilot Destination Environment

You can use the command below to set the destination environment for Autopilot.  

`site:autopilot:deployment-destination {SITE_NAME|SITE_ID} {{ENV}}`

### Get the Environment Syncing Status

You can use the command below to get the status of environment syncing. 

`site:autopilot:env-sync {SITE_NAME|SITE_ID}` 

### Enable or Disable Environment Syncing

You can explicitly set environment syncing. This syncs the target Autopilot environment to the Live environment before applying updates in an Autopilot cycle.

To **enable** environment syncing:

`site:autopilot:env-sync:enable`

To **disable** environment syncing:

`site:autopilot:env-sync:disable`

### Get Autopilot Frequency

You can use the command below to get the frequency at which Autopilot currently runs. 

`site:autopilot:frequency {SITE_NAME|SITE_ID}`

### Set Autopilot Frequency

You can use the command below to set the frequency at which Autopilot runs. Valid options are: 

- daily (for Platinum sites and above)
- weekly
- monthly
- manual

`site:autopilot:frequency {SITE_NAME|SITE_ID} {FREQUENCY}`

## Development

### Releases
Every merge to `main` is tagged and released automatically; see [DEPLOYMENT.md](DEPLOYMENT.md) for how releases work, how to skip one, and how to roll back.

## Rollback

**When:** a release of this plugin breaks users and a fix will take more than a few hours. Otherwise fix forward with the next patch tag.

**Recover:** revert the bad change on `main` and tag the next patch version. Never delete or move a published tag. Add `[ci skip]` to merge messages until the revert lands, because merging to `main` auto-releases. Users who already installed the bad version can pin the last good one with `terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin:<previous>`.

**Verify:** a clean scratch install (commands in [DEPLOYMENT.md](DEPLOYMENT.md#how-to-confirm-it-deployed)) shows the intended version, and Packagist lists the fixed tag first.

**Who to page:** the `developer-experience` team via `#ask-hosting`.

Full procedure: [DEPLOYMENT.md](DEPLOYMENT.md#rollback).
