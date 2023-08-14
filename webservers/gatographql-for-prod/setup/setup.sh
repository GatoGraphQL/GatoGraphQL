#!/bin/sh
if wp core is-installed --path=/app/wordpress; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
/bin/sh /app/setup/create-config.sh
/bin/sh /app/setup/install.sh
/bin/sh /app/setup/configure.sh
# /bin/sh /app/setup/fix-install.sh
/bin/sh /app/setup/install-safly-curl-patch.sh
/bin/sh /app/setup/activate-theme.sh
/bin/sh /app/setup/activate-plugins.sh
/bin/sh /app/setup/delete-data.sh
/bin/sh /app/setup/create-users.sh
/bin/sh /app/setup/add-user-application-passwords.sh
/bin/sh /app/setup/add-options.sh
/bin/sh /app/setup/configure-plugin-settings.sh
/bin/sh /app/setup/import-data.sh
/bin/sh /app/setup/create-menus.sh
# /bin/sh /app/setup/create-taxonomy-terms.sh
