#!/bin/bash
if wp core is-installed; then
    echo "WordPress is already installed"
    exit
fi

########################################################################
# Inputs
# ----------------------------------------------------------------------
LOCALHOST_WEBSERVER_DOMAIN="$1"

########################################################################
# Required vars
# ----------------------------------------------------------------------
WEBSERVER_DOMAIN=$(/bin/bash /app/_shared-webserver/utils/calculate-webserver-domain.sh "$LOCALHOST_WEBSERVER_DOMAIN")

echo "# ----------------------------------------------------------------------"
echo "# LOCALHOST_WEBSERVER_DOMAIN: '${LOCALHOST_WEBSERVER_DOMAIN}'"
echo "# PROXIED_WEBSERVER_DOMAIN: '${PROXIED_WEBSERVER_DOMAIN}'"
echo "# MAYBE_ENABLED_PROXIED_WEBSERVER_DOMAIN: '${MAYBE_ENABLED_PROXIED_WEBSERVER_DOMAIN}'"
echo "# WEBSERVER_DOMAIN: '${WEBSERVER_DOMAIN}'"
echo "# ----------------------------------------------------------------------"
echo "# Installing WordPress + Gato GraphQL in '${WEBSERVER_DOMAIN}'"
echo "# ----------------------------------------------------------------------"

/bin/bash /app/_shared-webserver/setup/create-config.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/install.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/configure.sh "$WEBSERVER_DOMAIN"
# /bin/bash /app/_shared-webserver/setup/fix-install.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/install-safly-curl-patch.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/activate-theme.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/activate-plugins.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/delete-data.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/create-users.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/add-user-application-passwords.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/add-options.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/configure-plugin-settings.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/import-data.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/create-menus.sh "$WEBSERVER_DOMAIN"
/bin/bash /app/_shared-webserver/setup/trigger-setup-data-install.sh "$WEBSERVER_DOMAIN"
# /bin/bash /app/_shared-webserver/setup/create-taxonomy-terms.sh "$WEBSERVER_DOMAIN"
