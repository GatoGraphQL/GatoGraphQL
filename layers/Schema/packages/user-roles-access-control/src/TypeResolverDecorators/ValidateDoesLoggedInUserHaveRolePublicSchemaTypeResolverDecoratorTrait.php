<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;

trait ValidateDoesLoggedInUserHaveRolePublicSchemaTypeResolverDecoratorTrait
{
    /**
     * By default, only the admin can see the roles from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    protected function getMandatoryDirectives($entryValue = null): array
    {
        $roles = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveResoverClass = $this->getValidateRoleDirectiveResolverClass();
        $directiveName = $directiveResoverClass::getDirectiveName();
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

    abstract protected function getValidateRoleDirectiveResolverClass(): string;
}
