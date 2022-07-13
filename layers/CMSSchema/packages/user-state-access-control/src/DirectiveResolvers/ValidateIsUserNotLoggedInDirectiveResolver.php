<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\UserState\Checkpoints\UserNotLoggedInCheckpoint;
use PoPCMSSchema\UserStateAccessControl\FeedbackItemProviders\FeedbackItemProvider;

class ValidateIsUserNotLoggedInDirectiveResolver extends AbstractValidateCheckpointDirectiveResolver
{
    private ?UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint = null;

    final public function setUserNotLoggedInCheckpoint(UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint): void
    {
        $this->userNotLoggedInCheckpoint = $userNotLoggedInCheckpoint;
    }
    final protected function getUserNotLoggedInCheckpoint(): UserNotLoggedInCheckpoint
    {
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
     * @param FieldInterface[] $failedFields
     */
    protected function getValidationFailedFeedbackItemResolution(RelationalTypeResolverInterface $relationalTypeResolver, array $failedFields): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            $this->isValidatingDirective() ? FeedbackItemProvider::E3 : FeedbackItemProvider::E4,
            [
                implode(
                    $this->__('\', \''),
                    array_map(
                        fn (FieldInterface $field) => $field->asASTNodeString(),
                        $failedFields
                    )
                ),
                $relationalTypeResolver->getMaybeNamespacedTypeName(),
            ]
        );
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It validates if the user is not logged-in', 'component-model');
    }
}
