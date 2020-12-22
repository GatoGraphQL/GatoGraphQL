<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlGroups;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;

class DisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(AccessControlGroups::DISABLED);
    }
}
