<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveRoleForDirectivesPrivateSchemaRelationalTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaRelationalTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::ROLES);
    }
}
