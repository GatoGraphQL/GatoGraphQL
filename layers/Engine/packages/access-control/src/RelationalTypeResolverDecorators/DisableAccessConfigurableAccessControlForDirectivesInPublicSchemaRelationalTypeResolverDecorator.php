<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlGroups;

class DisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::DISABLED);
    }
}
