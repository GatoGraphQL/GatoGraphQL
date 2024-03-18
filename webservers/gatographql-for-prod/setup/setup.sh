#!/bin/bash
if wp core is-installed; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
/bin/bash /app/setup/create-config.sh
/bin/bash /app/setup/install.sh
/bin/bash /app/setup/configure.sh
# /bin/bash /app/setup/fix-install.sh
/bin/bash /app/setup/install-safly-curl-patch.sh
/bin/bash /app/setup/activate-theme.sh
/bin/bash /app/setup/activate-plugins.sh
/bin/bash /app/setup/delete-data.sh
/bin/bash /app/setup/create-users.sh
/bin/bash /app/setup/add-user-application-passwords.sh
/bin/bash /app/setup/add-options.sh
/bin/bash /app/setup/configure-plugin-settings.sh
/bin/bash /app/setup/import-data.sh
/bin/bash /app/setup/create-menus.sh
/bin/bash /app/setup/trigger-setup-data-install.sh
# /bin/bash /app/setup/create-taxonomy-terms.sh
