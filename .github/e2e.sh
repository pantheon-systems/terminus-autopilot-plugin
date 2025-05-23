#!/usr/bin/env bash
set -euo pipefail

VCS_REF=$(git rev-parse --short HEAD)
SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")
ROOT_DIR=$(dirname "$SCRIPTPATH")
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;")
SITENAME="ci-php${PHP_VERSION//./}-t${TERMINUS_VERSION//./}-${VCS_REF}"

echo "===================================================="
echo "Root Dir: ${ROOT_DIR}"
echo "Testing Site: ${SITENAME}"
echo "Terminus org: ${TERMINUS_ORG}"
echo "Terminus version: ${TERMINUS_VERSION}"
echo "===================================================="

echo "Installing Plugin: "
terminus self:plugin:install "${ROOT_DIR}"

if terminus site:info "${SITENAME}" 2&>/dev/null; then
  echo "Site ${SITENAME} already exists. Deleting it..."
  terminus site:delete "${SITENAME}" --yes --quiet &> /dev/null
fi

echo "Creating Site: ${SITENAME}"
terminus site:create "${SITENAME}" "${SITENAME}" wordpress --org="${TERMINUS_ORG}"
echo "Installing Site: ${SITENAME}"
terminus remote:wp "${SITENAME}".dev -- core install \
        --title="$SITENAME" \
        --admin_user=admin \
        --admin_email=admin@mysite.com \
        --skip-email

echo "Setting Connection: ${SITENAME}"
## set the connection of the site to GIT mode
terminus connection:set "${SITENAME}.dev" git

echo "Activating Autopilot: ${SITENAME}"
terminus site:autopilot:activate "$SITENAME"

echo "Enable Env sync"
terminus site:autopilot:env-sync:enable "${SITENAME}"

echo "Setting monthly frequency"
terminus site:autopilot:frequency "${SITENAME}" monthly

echo "Setting Deployment Destination"
terminus site:autopilot:deployment-destination "${SITENAME}" test
terminus site:autopilot:deployment-destination "${SITENAME}" none

echo "Deactivating Autopilot"
terminus site:autopilot:deactivate "${SITENAME}"

echo "Deleting test site"
terminus site:delete "${SITENAME}" --yes --quiet
