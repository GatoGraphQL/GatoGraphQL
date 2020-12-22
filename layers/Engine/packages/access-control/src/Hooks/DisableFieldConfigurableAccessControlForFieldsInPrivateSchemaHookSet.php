<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\Services\AccessControlGroups;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet;

class DisableFieldConfigurableAccessControlForFieldsInPrivateSchemaHookSet extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet
{
    /**
     * Configuration entries
     *
     * @return array
     */
    protected static function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(AccessControlGroups::DISABLED);
    }
}
