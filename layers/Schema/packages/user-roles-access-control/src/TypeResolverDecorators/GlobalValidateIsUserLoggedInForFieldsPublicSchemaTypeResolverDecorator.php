<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\UserStateAccessControl\TypeResolverDecorators\AbstractValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;

class GlobalValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator extends AbstractValidateIsUserLoggedInForFieldsPublicSchemaTypeResolverDecorator
{
    public function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     */
    protected function getDirectiveResolverClasses(): array
    {
        return [
            ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::class,
            ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver::class,
        ];
    }
}
