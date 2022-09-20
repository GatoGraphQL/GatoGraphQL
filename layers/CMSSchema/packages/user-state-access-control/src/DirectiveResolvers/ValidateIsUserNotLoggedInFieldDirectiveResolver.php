<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointFieldDirectiveResolver;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\UserState\Checkpoints\UserNotLoggedInCheckpoint;
use PoPCMSSchema\UserStateAccessControl\FeedbackItemProviders\FeedbackItemProvider;

class ValidateIsUserNotLoggedInFieldDirectiveResolver extends AbstractValidateCheckpointFieldDirectiveResolver
{
    private ?UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint = null;

    final public function setUserNotLoggedInCheckpoint(UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint): void
    {
        $this->userNotLoggedInCheckpoint = $userNotLoggedInCheckpoint;
    }
    final protected function getUserNotLoggedInCheckpoint(): UserNotLoggedInCheckpoint
    {
        /** @var UserNotLoggedInCheckpoint */
        return $this->userNotLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserNotLoggedInCheckpoint::class);
    }

    public function getDirectiveName(): string
    {
        return 'validateIsUserNotLoggedIn';
    }

    /**
     * @return CheckpointInterface[]
     */
    protected function getValidationCheckpoints(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            $this->getUserNotLoggedInCheckpoint(),
        ];
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
        $fieldIDs = MethodHelpers::orderIDsByDirectFields($idFieldSet);
        /** @var FieldInterface $field */
        foreach ($fieldIDs as $field) {
            /** @var array<string|int> */
            $ids = $fieldIDs[$field];
            $engineIterationFeedbackStore->objectResolutionFeedbackStore->addError(
                new ObjectResolutionFeedback(
                    new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        $this->isValidatingDirective() ? FeedbackItemProvider::E3 : FeedbackItemProvider::E4,
                        [
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
        return $this->__('It validates if the user is not logged-in', 'component-model');
    }
}
