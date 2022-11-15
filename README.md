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

### Early Access

The Terminus Autopilot plugin is available for Early Access participants. Features for the Terminus Autopilot plugin are in active development. Pantheon's development team is rolling out new functionality often while this product is in Early Access. Visit the [Pantheon Slack channel](https://slackin.pantheon.io/) (or sign up for the channel if you don't already have an account) to learn how you can enroll in our Early Access program. Please review [Pantheon's Software Evaluation Licensing Terms](https://legal.pantheon.io/#contract-hkqlbwpxo) for more information about access to our software.

### Terminus Autopilot Plugin Requirements

Autopilot requires the following:

- A site with Autopilot available
- Terminus 3

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

### Enable or Disable Environment Syncing

You can explicitly set environment syncing. This syncs the target Autopilot environment to the Live environment before applying updates in an Autopilot cycle.

To **enable** environment syncing:

`site:autopilot:env-sync:enable`

To **disable** environment syncing:

`site:autopilot:env-sync:disable`

## Set Autopilot Frequency

You can use the command below to set the frequency at which Autopilot runs. Valid options are: 

- daily (for Platinum sites and above)
- weekly
- monthly
- manual

`site:autopilot:frequency {FREQUENCY}`
