#!/usr/bin/env bash
## AUTOPILOT & SECRETS **INTERNAL** END-TO-END TEST SETUP

## Must be part of an org with Autopilot enabled
## e.g. 5ae1fa30-8cc4-4894-8ca9-d50628dcba17 is the
## pantheon CI org.

## terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin

terminus auth:login -n --machine-token="$TERMINUS_TOKEN"

export CI_ORG_ID=5ae1fa30-8cc4-4894-8ca9-d50628dcba17
export SITENAME="TAP-${CIRCLE_BUILD_NUM}"
export EXISTS=$(terminus site:info "${SITENAME}" --field=id --format=json)

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

## export the URL for the dev environment
export SITE_DEV=https://`terminus env:info $SITENAME.dev --format=json --field=domain`

## curl the page once to make sure you initialize all the database tables
curl $SITE_DEV

terminus env:deploy ${SITENAME}.test

terminus env:deploy ${SITENAME}.live

TOKEN=$(cat ~/.terminus/cache/session | jq -r .session)
TESTING_TOKEN="{$TERMINUS_TOKEN:=$TOKEN}"

TERMINUS_SITE_UUID=$(terminus site:info ${SITENAME} --field=id --format=json 2>&1)

TERMINUS_TOKEN=${TESTING_TOKEN} \
  TERMINUS_SITE_UUID=${TERMINUS_SITE_UUID}  \
  TERMINUS_IS_TESTING_ENV=TRUE \
  TERMINUS_SITE=${SITENAME} \
  phpunit --colors=always tests

terminus site:delete ${SITENAME} --yes
