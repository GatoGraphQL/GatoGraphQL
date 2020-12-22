<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator;
use PoPSchema\UserStateAccessControl\TypeResolverDecorators\UserStateConfigurableAccessControlInPublicSchemaTypeResolverDecoratorTrait;

abstract class AbstractUserStateConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator
{
    use UserStateConfigurableAccessControlInPublicSchemaTypeResolverDecoratorTrait;

    protected static function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(AccessControlGroups::STATE);
    }
}
