<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\ConditionalOnComponent\CacheControl\RelationalTypeResolverDecorators;

use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveCapabilityForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaRelationalTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::CAPABILITIES);
    }
}
