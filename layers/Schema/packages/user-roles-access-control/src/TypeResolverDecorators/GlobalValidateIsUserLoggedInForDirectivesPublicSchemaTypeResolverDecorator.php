<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoPSchema\UserStateAccessControl\TypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator;

class GlobalValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator extends AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractObjectTypeResolver::class,
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
