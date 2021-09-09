<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoPSchema\UserStateAccessControl\TypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;

class GlobalValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator extends AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     */
    protected function getDirectiveResolverClasses(): array
    {
        return [
            ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver::class,
            ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver::class,
        ];
    }
}
