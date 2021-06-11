<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\DirectiveResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;

class ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateDoesLoggedInUserHaveAnyRole';
    }

    protected function validateCondition(TypeResolverInterface $typeResolver): bool
    {
        $vars = ApplicationState::getVars();
        // If the user is not logged-in, then do nothing: directive `@validateIsUserLoggedIn` will already fail
        if (!$vars['global-userstate']['is-user-logged-in']) {
            return true;
        }

        $roles = $this->directiveArgsForSchema['roles'];
        $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
        $userID = $vars['global-userstate']['current-user-id'];
        $userRoles = $userRoleTypeDataResolver->getUserRoles($userID);
        return !empty(array_intersect($roles, $userRoles));
    }

    protected function getValidationFailedMessage(TypeResolverInterface $typeResolver, array $failedDataFields): string
    {
        $roles = $this->directiveArgsForSchema['roles'];
        $isValidatingDirective = $this->isValidatingDirective();
        if (count($roles) == 1) {
            $errorMessage = $isValidatingDirective ?
                $this->translationAPI->__('You must have role \'%s\' to access directives in field(s) \'%s\' for type \'%s\'', 'user-roles') :
                $this->translationAPI->__('You must have role \'%s\' to access field(s) \'%s\' for type \'%s\'', 'user-roles');
        } else {
            $errorMessage = $isValidatingDirective ?
                $this->translationAPI->__('You must have any role from among \'%s\' to access directives in field(s) \'%s\' for type \'%s\'', 'user-roles') :
                $this->translationAPI->__('You must have any role from among \'%s\' to access field(s) \'%s\' for type \'%s\'', 'user-roles');
        }
        return sprintf(
            $errorMessage,
            implode(
                $this->translationAPI->__('\', \''),
                $roles
            ),
            implode(
                $this->translationAPI->__('\', \''),
                $failedDataFields
            ),
            $typeResolver->getMaybeNamespacedTypeName()
        );
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('It validates if the user has any of the roles provided through directive argument \'roles\'', 'component-model');
    }
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'roles',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_IS_ARRAY => true,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Roles to validate if the logged-in user has (any of them)', 'component-model'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
        ];
    }
}
