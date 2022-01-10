<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;

class ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        return $this->userRoleTypeAPI ??= $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'validateDoesLoggedInUserHaveAnyCapability';
    }

    protected function validateCondition(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $vars = ApplicationState::getVars();
        // If the user is not logged-in, then do nothing: directive `@validateIsUserLoggedIn` will already fail
        if (!\PoP\Root\App::getState('is-user-logged-in')) {
            return true;
        }

        $capabilities = $this->directiveArgsForSchema['capabilities'];
        $userID = \PoP\Root\App::getState('current-user-id');
        $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($userID);
        return !empty(array_intersect($capabilities, $userCapabilities));
    }

    protected function getValidationFailedMessage(RelationalTypeResolverInterface $relationalTypeResolver, array $failedDataFields): string
    {
        $capabilities = $this->directiveArgsForSchema['capabilities'];
        $isValidatingDirective = $this->isValidatingDirective();
        if (count($capabilities) == 1) {
            $errorMessage = $isValidatingDirective ?
                $this->__('You must have capability \'%s\' to access directives in field(s) \'%s\' for type \'%s\'', 'user-roles') :
                $this->__('You must have capability \'%s\' to access field(s) \'%s\' for type \'%s\'', 'user-roles');
        } else {
            $errorMessage = $isValidatingDirective ?
                $this->__('You must have any capability from among \'%s\' to access directives in field(s) \'%s\' for type \'%s\'', 'user-roles') :
                $this->__('You must have any capability from among \'%s\' to access field(s) \'%s\' for type \'%s\'', 'user-roles');
        }
        return sprintf(
            $errorMessage,
            implode(
                $this->__('\', \''),
                $capabilities
            ),
            implode(
                $this->__('\', \''),
                $failedDataFields
            ),
            $relationalTypeResolver->getMaybeNamespacedTypeName()
        );
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It validates if the user has any capability provided through directive argument \'capabilities\'', 'component-model');
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            'capabilities' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'capabilities' => $this->__('Capabilities to validate if the logged-in user has (any of them)', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'capabilities' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }
}
