<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaTypeResolverDecorator;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;

abstract class AbstractNoCacheConfigurableAccessControlForDirectivesInPrivateSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForDirectives(AccessControlGroups::STATE);
    }
}
