<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlGroups;
use PoP\AccessControl\TypeResolverDecorators\AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;

class DisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForDirectives(AccessControlGroups::DISABLED);
    }
}
