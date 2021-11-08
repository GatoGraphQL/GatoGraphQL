<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator;

class GlobalValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator
{
    private ?ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver = null;
    private ?ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver
    {
        return $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver::class);
    }
    final public function setValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver
    {
        return $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver::class);
    }

    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    /**
     * Provide the DirectiveResolvers that need the "validateIsUserLoggedIn" directive
     *
     * @return DirectiveResolverInterface[]
     */
    protected function getDirectiveResolvers(): array
    {
        return [
            $this->getValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver(),
            $this->getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver(),
        ];
    }
}
