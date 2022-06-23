<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

trait ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait
{
    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;

    /**
     * By default, only the admin can see the roles from the users
     *
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $roles = $entryValue;
        return [
            $this->getValidateDoesLoggedInUserHaveAnyRoleDirective($roles),
        ];
    }

    /**
     * @param string[] $roles
     */
    protected function getValidateDoesLoggedInUserHaveAnyRoleDirective(array $roles): Directive
    {
        if ($this->validateDoesLoggedInUserHaveAnyCapabilityDirective === null) {
            $this->validateDoesLoggedInUserHaveAnyCapabilityDirective = new Directive(
                $this->getValidateRoleDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'roles',
                        new InputList(
                            $roles,
                            LocationHelper::getNonSpecificLocation()
                        ),
                        LocationHelper::getNonSpecificLocation()
                    ),
                ],
                LocationHelper::getNonSpecificLocation()
            );
        }
        return $this->validateDoesLoggedInUserHaveAnyCapabilityDirective;
    }

    abstract protected function getValidateRoleDirectiveResolver(): DirectiveResolverInterface;
}
