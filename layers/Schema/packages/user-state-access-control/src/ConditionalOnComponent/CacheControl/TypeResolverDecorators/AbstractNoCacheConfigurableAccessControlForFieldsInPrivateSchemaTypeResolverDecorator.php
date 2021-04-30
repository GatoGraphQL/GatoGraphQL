<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;

abstract class AbstractNoCacheConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForFields(AccessControlGroups::STATE);
    }
}
