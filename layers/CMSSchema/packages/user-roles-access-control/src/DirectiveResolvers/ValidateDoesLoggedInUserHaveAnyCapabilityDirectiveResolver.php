<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\UserRolesAccessControl\FeedbackItemProviders\FeedbackItemProvider;

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
        // If the user is not logged-in, then do nothing: directive `@validateIsUserLoggedIn` will already fail
        if (!App::getState('is-user-logged-in')) {
            return true;
        }

        $capabilities = $this->directiveArgsForSchema['capabilities'];
        $userID = App::getState('current-user-id');
        $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($userID);
        return !empty(array_intersect($capabilities, $userCapabilities));
    }

    protected function getValidationFailedFeedbackItemResolution(RelationalTypeResolverInterface $relationalTypeResolver, array $failedDataFields): FeedbackItemResolution
    {
        $capabilities = $this->directiveArgsForSchema['capabilities'];
        $isValidatingDirective = $this->isValidatingDirective();
        $code = (count($capabilities) === 1)
            ? ($isValidatingDirective ? FeedbackItemProvider::E1 : FeedbackItemProvider::E2)
            : ($isValidatingDirective ? FeedbackItemProvider::E3 : FeedbackItemProvider::E4);

        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            $code,
            [
                implode(
                    $this->__('\', \''),
                    $capabilities
                ),
                implode(
                    $this->__('\', \''),
                    $failedDataFields
                ),
                $relationalTypeResolver->getMaybeNamespacedTypeName(),
            ]
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
