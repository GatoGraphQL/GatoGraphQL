<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\CommentMutations\Constants\CommentCRUDHookNames;
use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractDeleteCommentMutationResolver extends AbstractEditCommentMutationResolver
{
    use DeleteCommentMutationResolverTrait;

    protected function validateDeleteErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $commentID = $this->getCommentID($fieldDataAccessor);

        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCommentExists($commentID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int */
        $commentID = $commentID;

        $this->validateCanLoggedInUserEditComment(
            $commentID,
            MutationErrorFeedbackItemProvider::E12,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanCommentBeTrashed($commentID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        // Allow components to inject their own validations
        App::doAction(
            CommentCRUDHookNames::VALIDATE_DELETE_COMMENT,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * When not deleting permanently, the comment must support being
     * sent to the trash, and must not be in the trash already.
     */
    protected function validateCanCommentBeTrashed(
        string|int $commentID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->isForceDelete($fieldDataAccessor)) {
            return;
        }

        if (!$this->getCommentTypeMutationAPI()->doesCommentSupportTrash()) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E13,
                        [
                            $commentID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        if ($this->getCommentTypeMutationAPI()->isCommentInTrash($commentID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E14,
                        [
                            $commentID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function isForceDelete(FieldDataAccessorInterface $fieldDataAccessor): bool
    {
        return $fieldDataAccessor->getValue(MutationInputProperties::FORCE) === true;
    }

    /**
     * @return bool Whether the comment was deleted
     * @throws CommentCRUDMutationException If there was an error (eg: Comment does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool {
        /** @var string|int */
        $commentID = $this->getCommentID($fieldDataAccessor);

        if ($this->isForceDelete($fieldDataAccessor)) {
            $this->getCommentTypeMutationAPI()->deleteComment($commentID);
        } else {
            $this->getCommentTypeMutationAPI()->trashComment($commentID);
        }

        App::doAction(
            CommentCRUDHookNames::EXECUTE_DELETE_COMMENT,
            $commentID,
            $fieldDataAccessor,
        );

        return true;
    }
}
