<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveCapabilityPublicSchemaTypeResolverDecoratorTrait;

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }

    protected function getValidateCapabilityDirectiveResolverClass(): string
    {
        return ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver::class;
    }
}
