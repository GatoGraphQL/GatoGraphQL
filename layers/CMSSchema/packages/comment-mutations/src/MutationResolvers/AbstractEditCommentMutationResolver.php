<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

/**
 * Shared validation for the mutations that operate on an existing
 * comment: updating it, moderating it, and deleting it.
 */
abstract class AbstractEditCommentMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentTypeMutationAPIInterface $commentTypeMutationAPI = null;

    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        if ($this->commentTypeAPI === null) {
            /** @var CommentTypeAPIInterface */
            $commentTypeAPI = $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
            $this->commentTypeAPI = $commentTypeAPI;
        }
        return $this->commentTypeAPI;
    }

    final protected function getCommentTypeMutationAPI(): CommentTypeMutationAPIInterface
    {
        if ($this->commentTypeMutationAPI === null) {
            /** @var CommentTypeMutationAPIInterface */
            $commentTypeMutationAPI = $this->instanceManager->getInstance(CommentTypeMutationAPIInterface::class);
            $this->commentTypeMutationAPI = $commentTypeMutationAPI;
        }
        return $this->commentTypeMutationAPI;
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    /**
     * Validate that the comment exists. The ID is mandatory in the input,
     * so a missing ID should never happen.
     */
    protected function validateCommentExists(
        string|int|null $commentID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$commentID || $this->getCommentTypeAPI()->getComment($commentID) === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E10,
                        [
                            $commentID ?? '',
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    /**
     * Check that the logged-in user can edit this specific comment.
     *
     * The `edit_comment` meta capability is resolved by the CMS against
     * the comment's custom post, hence this single check also covers
     * moderating and deleting comments on others' custom posts.
     */
    protected function validateCanLoggedInUserEditComment(
        string|int $commentID,
        string $errorCode,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $userID = App::getState('current-user-id');
        if ($this->getCommentTypeMutationAPI()->canUserEditComment($userID, $commentID)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    $errorCode,
                    [
                        $commentID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function getCommentID(FieldDataAccessorInterface $fieldDataAccessor): string|int|null
    {
        /** @var string|int|null */
        return $fieldDataAccessor->getValue(MutationInputProperties::ID);
    }
}
