<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;
use PoPSchema\UserStateAccessControl\TypeResolverDecorators\UserStateConfigurableAccessControlInPublicSchemaTypeResolverDecoratorTrait;

abstract class AbstractUserStateConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    use UserStateConfigurableAccessControlInPublicSchemaTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForDirectives(AccessControlGroups::STATE);
    }
}
