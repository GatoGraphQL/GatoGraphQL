<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPrivateSchemaTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }
}
