<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator;

abstract class AbstractUserStateConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    use UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForFields(AccessControlGroups::STATE);
    }
}
