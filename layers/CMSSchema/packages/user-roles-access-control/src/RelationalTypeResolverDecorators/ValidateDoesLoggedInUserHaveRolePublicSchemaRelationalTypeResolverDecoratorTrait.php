<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

trait ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait
{
    /** @var array<string,Directive> */
    protected array $validateDoesLoggedInUserHaveAnyRoleDirectives = [];

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
        $rolesKey = implode('|', $roles);
        if (!isset($this->validateDoesLoggedInUserHaveAnyRoleDirectives[$rolesKey])) {
            $this->validateDoesLoggedInUserHaveAnyRoleDirectives[$rolesKey] = new Directive(
                $this->getValidateRoleFieldDirectiveResolver()->getDirectiveName(),
                [
                    new Argument(
                        'roles',
                        new InputList(
                            $roles,
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                ],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->validateDoesLoggedInUserHaveAnyRoleDirectives[$rolesKey];
    }

    abstract protected function getValidateRoleFieldDirectiveResolver(): FieldDirectiveResolverInterface;
}
