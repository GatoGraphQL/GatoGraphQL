<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\RelationalTypeResolverDecorators;

use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPrivateSchemaRelationalTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaRelationalTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }
}
