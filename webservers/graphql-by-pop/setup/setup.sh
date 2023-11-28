#!/bin/bash
if wp core is-installed; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
/bin/sh /app/setup/create-config.sh
/bin/sh /app/setup/install.sh
/bin/sh /app/setup/configure.sh
/bin/sh /app/setup/load-custom-code.sh
/bin/sh /app/setup/install-safly-curl-patch.sh
/bin/sh /app/setup/activate-plugins.sh
