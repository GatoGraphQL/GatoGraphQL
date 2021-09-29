<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\ConfigurationEntries\AccessControlConfigurableMandatoryDirectivesForDirectivesTrait;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForDirectivesTrait;

abstract class AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet extends AbstractAccessControlForDirectivesInPrivateSchemaHookSet
{
    use AccessControlConfigurableMandatoryDirectivesForDirectivesHookSetTrait;
    use ConfigurableMandatoryDirectivesForDirectivesTrait, AccessControlConfigurableMandatoryDirectivesForDirectivesTrait {
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getMatchingEntries insteadof ConfigurableMandatoryDirectivesForDirectivesTrait;
        // The conflict resolutions below should not be needed, because the functions are not repeated, but it is defined just once in the same source trait
        // However, there is a bug about, still unresolved by PHP 7.2: https://bugs.php.net/bug.php?id=63911
        // It was resolved by PHP 7.3.9, though, but handle to add compatibility up to PHP 7.1
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getEntries insteadof ConfigurableMandatoryDirectivesForDirectivesTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getConfigurationEntries insteadof ConfigurableMandatoryDirectivesForDirectivesTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getRequiredEntryValue insteadof ConfigurableMandatoryDirectivesForDirectivesTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getDirectiveResolvers insteadof ConfigurableMandatoryDirectivesForDirectivesTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getDirectiveResolverClasses insteadof ConfigurableMandatoryDirectivesForDirectivesTrait;
    }

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getEntries());
    }
}
