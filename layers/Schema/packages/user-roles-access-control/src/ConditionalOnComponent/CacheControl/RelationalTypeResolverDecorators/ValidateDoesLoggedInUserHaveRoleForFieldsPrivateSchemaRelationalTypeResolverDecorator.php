<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\RelationalTypeResolverDecorators;

use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\RelationalTypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaRelationalTypeResolverDecorator;

class ValidateDoesLoggedInUserHaveRoleForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaRelationalTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForFields(AccessControlGroups::ROLES);
    }
}
