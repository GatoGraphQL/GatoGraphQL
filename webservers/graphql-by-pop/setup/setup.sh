#!/bin/bash
if wp core is-installed; then
    echo "WordPress is already installed"
    exit
fi
echo "Installing WordPress..."
/bin/bash /app/setup/create-config.sh
/bin/bash /app/setup/install.sh
/bin/bash /app/setup/configure.sh
/bin/bash /app/setup/load-custom-code.sh
/bin/bash /app/setup/install-safly-curl-patch.sh
/bin/bash /app/setup/activate-plugins.sh
