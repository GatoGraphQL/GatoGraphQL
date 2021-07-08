<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;

trait ValidateDoesLoggedInUserHaveRolePublicSchemaTypeResolverDecoratorTrait
{
    /**
     * By default, only the admin can see the roles from the users
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $roles = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveResolverClass = $this->getValidateRoleDirectiveResolverClass();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
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

    abstract protected function getValidateRoleDirectiveResolverClass(): string;
}
