<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;

trait ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait
{
    /**
     * By default, only the admin can see the roles from the users
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $roles = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveResolver = $this->getValidateRoleDirectiveResolver();
        $directiveName = $directiveResolver->getDirectiveName();
        $validateDoesLoggedInUserHaveAnyRoleDirective = $fieldQueryInterpreter->getDirective(
            $directiveName,
            [
                'roles' => $roles,
            ]
        );
        return [
            $validateDoesLoggedInUserHaveAnyRoleDirective,
        ];
    }

    abstract protected function getValidateRoleDirectiveResolver(): DirectiveResolverInterface;
}
