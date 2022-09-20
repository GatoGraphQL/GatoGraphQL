<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait;

    private ?ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver::class);
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::ROLES);
    }

    protected function getValidateRoleFieldDirectiveResolver(): FieldDirectiveResolverInterface
    {
        return $this->getValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver();
    }
}
