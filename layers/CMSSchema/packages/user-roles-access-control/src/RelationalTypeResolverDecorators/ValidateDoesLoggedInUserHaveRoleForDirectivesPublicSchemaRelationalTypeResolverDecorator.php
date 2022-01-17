<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait;

    private ?ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver
    {
        return $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver::class);
    }

    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::ROLES);
    }

    protected function getValidateRoleDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver();
    }
}
