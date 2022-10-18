#!/bin/bash
########################################################################
#
# This bash script is executed after downgrade_code.sh.
#
# Its purpose is to apply hacks to fix issues in the codebase
# which are not handled by Rector.
#
########################################################################

# Fix the return type in Trait
# @see https://github.com/rectorphp/rector/issues/6962
# @see https://github.com/leoloso/PoP/issues/1470
sed -i 's/function getItem($key): CacheItem/function getItem($key): \\Psr\\Cache\\CacheItemInterface/' vendor/symfony/cache/Traits/AbstractAdapterTrait.php
