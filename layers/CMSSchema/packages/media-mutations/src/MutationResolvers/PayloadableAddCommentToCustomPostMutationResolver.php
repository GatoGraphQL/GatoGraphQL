<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\MediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MediaMutations\ObjectModels\CommentAuthorEmailIsMissingErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\CommentAuthorNameIsMissingErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\CommentParentCommentDoesNotExistErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\CommentsAreNotOpenForCustomPostErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\CommentsAreNotSupportedByCustomPostTypeErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

class PayloadableAddCommentToCustomPostMutationResolver extends AddCommentToCustomPostMutationResolver
{
    use PayloadableMutationResolverTrait;

    /**
     * Validate the app-level errors when executing the mutation,
     * return them in the Payload.
     *
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        parent::validate($fieldDataAccessor, $separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createFailureObjectMutationPayload(
                array_map(
                    $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                )
            )->getID();
        }

        $commentID = null;
        try {
            /** @var string|int */
            $commentID = parent::executeMutation(
                $fieldDataAccessor,
                $separateObjectTypeFieldResolutionFeedbackStore,
            );
        } catch (CommentCRUDMutationException $commentCRUDMutationException) {
            return $this->createFailureObjectMutationPayload(
                [
                    $this->createGenericErrorPayloadFromPayloadClientException($commentCRUDMutationException),
                ]
            )->getID();
        }

        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createFailureObjectMutationPayload(
                array_map(
                    $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                ),
                $commentID
            )->getID();
        }

        /** @var string|int $commentID */
        return $this->createSuccessObjectMutationPayload($commentID)->getID();
    }

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match (
            [
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode()
            ]
        ) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
            ] => new UserIsNotLoggedInErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new CommentAuthorNameIsMissingErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new CommentAuthorEmailIsMissingErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E6,
            ] => new CommentParentCommentDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new CustomPostDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E8,
            ] => new CommentsAreNotSupportedByCustomPostTypeErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E9,
            ] => new CommentsAreNotOpenForCustomPostErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $feedbackItemResolution->getMessage(),
                $feedbackItemResolution->getNamespacedCode(),
            ),
        };
    }
}
