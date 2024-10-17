#!/bin/bash
/bin/bash /app/_shared-webserver-prod/setup/download-plugins.sh
/bin/bash /app/_shared-webserver/setup/setup.sh "$1"