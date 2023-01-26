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

terminus self:plugin:install ${ROOT_DIR}

## If exists is empty, create the site
terminus site:create "${SITENAME}" "${SITENAME}" drupal9 --org=${CI_ORG_ID}

## Wipe the site Database and install basic umami
terminus drush ${SITENAME}.dev -- \
     site:install --account-name=admin \
       --site-name=${SITENAME}  \
       --locale=en --yes demo_umami

## set the connection of the site to GIT mode
terminus connection:set ${SITENAME}.dev git

terminus site:autopilot:activate $SITENAME

## set the site's plan to basic paid plan
## terminus plan:set $SITENAME plan-basic_small-contract-annual-1

## export the URL for the dev environment
export SITE_DEV=https://`terminus env:info $SITENAME.dev --format=json --field=domain`

## curl the page once to make sure you initialize all the database tables
curl $SITE_DEV

## Deploy the dev site to test & live
terminus env:deploy ${SITENAME}.test && terminus env:deploy ${SITENAME}.live

terminus site:autopilot:env-sync:enable $SITENAME
terminus site:autopilot:frequency $SITENAME daily
terminus site:autopilot:deployment-destination $SITENAME test
