<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator;
use PoPCMSSchema\UserStateAccessControl\Services\AccessControlGroups;

abstract class AbstractNoCacheConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlRelationalTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::STATE);
    }
}
