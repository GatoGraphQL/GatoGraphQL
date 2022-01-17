<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator;
use PoPCMSSchema\UserStateAccessControl\Services\AccessControlGroups;

abstract class AbstractUserStateConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    use UserStateConfigurableAccessControlInPublicSchemaRelationalTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::STATE);
    }
}
