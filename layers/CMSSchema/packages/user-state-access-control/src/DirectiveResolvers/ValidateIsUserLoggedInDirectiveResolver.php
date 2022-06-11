<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;
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
     * @param FieldInterface[] $failedDataFields
     */
    protected function getValidationFailedFeedbackItemResolution(RelationalTypeResolverInterface $relationalTypeResolver, array $failedDataFields): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            $this->isValidatingDirective() ? FeedbackItemProvider::E1 : FeedbackItemProvider::E2,
            [
                implode(
                    $this->__('\', \''),
                    array_map(
                        fn (FieldInterface $field) => $field->asFieldOutputQueryString(),
                        $failedDataFields
                    )
                ),
                $relationalTypeResolver->getMaybeNamespacedTypeName(),
            ]
        );
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It validates if the user is logged-in', 'component-model');
    }
}
