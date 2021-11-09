<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators\AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator;

class GlobalValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator
{
    private ?ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver = null;
    private ?ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver
    {
        return $this->validateDoesLoggedInUserHaveAnyRoleDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::class);
    }
    final public function setValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver(ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver
    {
        return $this->validateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver::class);
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
            $this->getValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver(),
            $this->getValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver(),
        ];
    }
}
