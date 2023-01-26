#!/usr/bin/env bash

SCRIPT=$(readlink -f $0)
SCRIPTPATH=`dirname $SCRIPT`
ROOT_DIR=`dirname $SCRIPTPATH`
VERSION=$(cat .version)
VERSION_SAFE="${VERSION//./}"
SITENAME="${VERSION_SAFE}-api-testing"
CI_ORG_ID=5ae1fa30-8cc4-4894-8ca9-d50628dcba17

terminus site:delete "${SITENAME}" --yes --quiet &> /dev/null

echo "===================================================="
echo "Root Dir: ${ROOT_DIR}"
echo "Version: ${VERSION_SAFE}"
echo "Testing Site: ${SITENAME}"
echo "===================================================="
echo "Installing Plugin: "
terminus self:plugin:install ${ROOT_DIR}
echo "===================================================="
echo "Creating Site: ${SITENAME}"
## If exists is empty, create the site
terminus site:create "${SITENAME}" "${SITENAME}" drupal9 --org=${CI_ORG_ID}
echo "===================================================="
echo "Installing Site: ${SITENAME}"
## Wipe the site Database and install basic umami
terminus drush ${SITENAME}.dev -- \
     site:install --account-name=admin \
       --site-name=${SITENAME}  \
       --locale=en --yes demo_umami
echo "===================================================="
echo "Setting Connection: ${SITENAME}"
## set the connection of the site to GIT mode
terminus connection:set ${SITENAME}.dev git
echo "===================================================="
echo "Activating Autopilot: ${SITENAME}"
terminus site:autopilot:activate $SITENAME
echo "===================================================="

## set the site's plan to basic paid plan
## terminus plan:set $SITENAME plan-basic_small-contract-annual-1
## export the URL for the dev environment
export SITE_DEV=https://`terminus env:info $SITENAME.dev --format=json --field=domain`
echo "Loading Homepage: ${SITE_DEV}"
## curl the page once to make sure you initialize all the database tables
curl $SITE_DEV &> /dev/null
echo "===================================================="
echo "Deploy Env's:: ${SITENAME}"
## Deploy the dev site to test & live
terminus env:deploy ${SITENAME}.test && terminus env:deploy ${SITENAME}.live
echo "===================================================="
echo "Enable Env sync: ${SITENAME}"
terminus site:autopilot:env-sync:enable $SITENAME
echo "===================================================="
echo "Enable Env sync: ${SITENAME}"
terminus site:autopilot:frequency $SITENAME daily
echo "===================================================="
echo "Setting Frequency: ${SITENAME}"
terminus site:autopilot:deployment-destination $SITENAME test
echo "===================================================="
echo "Deleting test site: ${SITENAME}"
terminus site:delete "${SITENAME}" --yes --quiet
