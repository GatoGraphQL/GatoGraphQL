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
    }

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getEntries());
    }
}
