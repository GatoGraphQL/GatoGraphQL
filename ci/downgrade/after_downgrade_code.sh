#!/bin/bash
########################################################################
#
# This bash script is executed after downgrade_code.sh.
#
# Its purpose is to apply hacks to fix issues in the codebase
# which are not handled by Rector.
#
########################################################################

# Downgrading `[#Required]` to `@required` triggers a deprecation message on Symfony:
#   array(4) { ["type"]=> int(16384) ["message"]=> string(231) "Since symfony/dependency-injection 6.3: Relying on the "@required" annotation on method "PoP\Root\Hooks\AbstractHookSet::setInstanceManager()" is deprecated, use the "Symfony\Contracts\Service\Attribute\Required" attribute instead." ["file"]=> string(95) "/app/wordpress/wp-content/plugins/gatographql/vendor/symfony/deprecation-contracts/function.php" ["line"]=> int(25) }
# and the "php-error" class is added on the WP dashboard, adding an annoying whiteline
# when WP_DEBUG is true. Then, comment out triggering that deprecation
sed -i "s#(trigger_deprecation\('symfony/dependency-injection', '6\.3', 'Relying on the \"@required\" annotation)#//$1#" vendor/symfony/dependency-injection/Compiler/AutowireRequiredMethodsPass.php
