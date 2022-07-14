<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPCMSSchema\UserStateAccessControl\FeedbackItemProviders\FeedbackItemProvider;

class ValidateIsUserLoggedInDirectiveResolver extends AbstractValidateCheckpointDirectiveResolver
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        return $this->userLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
    }

    public function getDirectiveName(): string
    {
        return 'validateIsUserLoggedIn';
    }

    /**
     * @return CheckpointInterface[]
     */
    protected function getValidationCheckpoints(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            $this->getUserLoggedInCheckpoint(),
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
            $engineIterationFeedbackStore->objectFeedbackStore->addError(
                new ObjectResolutionFeedback(
                    new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        $this->isValidatingDirective() ? FeedbackItemProvider::E1 : FeedbackItemProvider::E2,
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
        return $this->__('It validates if the user is logged-in', 'component-model');
    }
}
