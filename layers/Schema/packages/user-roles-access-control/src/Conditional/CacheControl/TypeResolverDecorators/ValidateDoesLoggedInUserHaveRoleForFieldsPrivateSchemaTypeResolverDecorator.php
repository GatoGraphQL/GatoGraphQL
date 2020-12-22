<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator;

class ValidateDoesLoggedInUserHaveRoleForFieldsPrivateSchemaTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator
{
    protected static function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(AccessControlGroups::ROLES);
    }
}
