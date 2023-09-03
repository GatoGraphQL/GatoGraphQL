#!/bin/bash
########################################################################
#
# This bash script is executed before downgrade_code.sh.
#
# Its purpose is to apply hacks to fix issues in the codebase
# in preparation for the Rector downgrade.
#
########################################################################

# Bug in Symfony Polyfill PHP 8.3: #[\SensitiveParameter] is available since PHP 8.2, but loaded on 8.1
# @see https://github.com/symfony/polyfill/issues/445
sed -i 's/\#\[\\SensitiveParameter\]//' vendor/symfony/polyfill-php83/bootstrap81.php
