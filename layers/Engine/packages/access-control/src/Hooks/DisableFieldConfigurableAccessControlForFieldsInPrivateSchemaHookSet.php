<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\Services\AccessControlGroups;

class DisableFieldConfigurableAccessControlForFieldsInPrivateSchemaHookSet extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet
{
    /**
     * Configuration entries
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::DISABLED);
    }
}
