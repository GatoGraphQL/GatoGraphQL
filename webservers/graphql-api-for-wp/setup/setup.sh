#!/bin/sh
if wp core is-installed --path=/app/wordpress; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
/bin/sh /app/setup/create-config.sh
/bin/sh /app/setup/install.sh
/bin/sh /app/setup/configure.sh
/bin/sh /app/setup/fix-install.sh
/bin/sh /app/setup/install-safly-curl-patch.sh
/bin/sh /app/setup/activate-plugins.sh
/bin/sh /app/setup/import-data.sh
/bin/sh /app/setup/create-users.sh
