<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators\AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator;

class GlobalValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator
{
    private ?ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver = null;
    private ?ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver::class);
    }
    final public function setValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver::class);
    }

    /**
     * @return array<class-string<RelationalTypeResolverInterface>|string> Either the class, or the constant "*" to represent _any_ class
     */
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
    protected function getFieldDirectiveResolvers(): array
    {
        return [
            $this->getValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver(),
            $this->getValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver(),
        ];
    }
}
