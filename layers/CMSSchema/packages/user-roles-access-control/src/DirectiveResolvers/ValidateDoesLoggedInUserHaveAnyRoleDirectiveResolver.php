<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionFieldDirectiveResolver;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\UserRolesAccessControl\FeedbackItemProviders\FeedbackItemProvider;

class ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver extends AbstractValidateConditionFieldDirectiveResolver
{
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        /** @var UserRoleTypeAPIInterface */
        return $this->userRoleTypeAPI ??= $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'validateDoesLoggedInUserHaveAnyRole';
    }

    protected function isValidationSuccessful(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        // If the user is not logged-in, then do nothing: directive `@validateIsUserLoggedIn` will already fail
        if (!App::getState('is-user-logged-in')) {
            return true;
        }

        $directiveArgs = $this->directiveDataAccessor->getDirectiveArgs();
        $roles = $directiveArgs['roles'];
        $userID = App::getState('current-user-id');
        $userRoles = $this->getUserRoleTypeAPI()->getUserRoles($userID);
        return !empty(array_intersect($roles, $userRoles));
    }

    /**
     * Add the errors to the FeedbackStore
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function addUnsuccessfulValidationErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $directiveArgs = $this->directiveDataAccessor->getDirectiveArgs();
        $roles = $directiveArgs['roles'];
        $isValidatingDirective = $this->isValidatingDirective();
        $code = (count($roles) === 1)
            ? ($isValidatingDirective ? FeedbackItemProvider::E5 : FeedbackItemProvider::E6)
            : ($isValidatingDirective ? FeedbackItemProvider::E7 : FeedbackItemProvider::E8);

        $fieldIDs = MethodHelpers::orderIDsByDirectFields($idFieldSet);
        /** @var FieldInterface $field */
        foreach ($fieldIDs as $field) {
            /** @var array<string|int> */
            $ids = $fieldIDs[$field];
            $engineIterationFeedbackStore->objectResolutionFeedbackStore->addError(
                new ObjectResolutionFeedback(
                    new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        $code,
                        [
                            implode(
                                $this->__('\', \''),
                                $roles
                            ),
                            $field->asFieldOutputQueryString(),
                            $relationalTypeResolver->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $field,
                    $relationalTypeResolver,
                    $this->directive,
                    $this->getFieldIDSetForField($field, $ids),
                )
            );
        }
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It validates if the user has any of the roles provided through directive argument \'roles\'', 'component-model');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                'roles' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'roles' => $this->__('Roles to validate if the logged-in user has (any of them)', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'roles' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }
}
