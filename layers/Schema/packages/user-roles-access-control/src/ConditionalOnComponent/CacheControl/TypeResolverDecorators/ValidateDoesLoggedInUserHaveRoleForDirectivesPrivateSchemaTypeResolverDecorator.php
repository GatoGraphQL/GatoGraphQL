<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators;

use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator;

class ValidateDoesLoggedInUserHaveRoleForDirectivesPrivateSchemaTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForDirectives(AccessControlGroups::ROLES);
    }
}
