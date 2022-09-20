<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver;
use PoPCMSSchema\UserStateAccessControl\RelationalTypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator;

class GlobalValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator
{
    private ?ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver = null;
    private ?ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver::class);
    }
    final public function setValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver::class);
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
     * @return FieldDirectiveResolverInterface[]
     */
    protected function getFieldDirectiveResolvers(): array
    {
        return [
            $this->getValidateDoesLoggedInUserHaveAnyRoleForDirectivesFieldDirectiveResolver(),
            $this->getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver(),
        ];
    }
}
