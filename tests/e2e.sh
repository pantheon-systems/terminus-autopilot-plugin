!/usr/bin/env bash

terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin

VERSION=$(cat .version)
VERSION_SAFE="${VERSION//./}"

CI_ORG_ID=5ae1fa30-8cc4-4894-8ca9-d50628dcba17
SITENAME="${$1:=$VERSION_SAFE-api-testing}"
EXISTS=$(terminus site:info "${SITENAME}" --field=id --format=json 2>&1)

## If exists is empty, create the site
if test -z "${EXISTS}"
then
  echo "Site does not exist, creating... ${EXISTS}"
  terminus site:create "${SITENAME}" "${SITENAME}" drupal9 --org=${CI_ORG_ID}
fi

## Wipe the site Database and install basic umami
terminus drush ${SITENAME}.dev -- \
     site:install --account-name=admin \
       --site-name=${SITENAME}  \
       --locale=en --yes demo_umami

## set the connection of the site to GIT mode
terminus connection:set ${SITENAME}.dev git

## set the site's plan to basic paid plan
## terminus plan:set $SITENAME plan-basic_small-contract-annual-1

## export the URL for the dev environment
export SITE_DEV=https://`terminus env:info $SITENAME.dev --format=json --field=domain`

## curl the page once to make sure you initialize all the database tables
curl $SITE_DEV

## Deploy the dev site to test & live
terminus env:deploy ${SITENAME}.test && terminus env:deploy ${SITENAME}.live

terminus site:autopilot:activate $SITENAME
terminus site:autopilot:env-sync:enable $SITENAME
terminus site:autopilot:frequency $SITENAME daily
terminus site:autopilot:deployment-destination $SITENAME test
