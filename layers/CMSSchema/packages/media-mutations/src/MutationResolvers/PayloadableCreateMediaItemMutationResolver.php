<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoPCMSSchema\MediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MediaMutations\ObjectModels\MediaItemSourceIsMissingErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\UserHasNoPermissionToUploadFilesErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\CommentsAreNotOpenForCustomPostErrorPayload;
use PoPCMSSchema\MediaMutations\ObjectModels\UserDoesNotExistErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

class PayloadableCreateMediaItemMutationResolver extends CreateMediaItemMutationResolver
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
        } catch (MediaItemCRUDMutationException $commentCRUDMutationException) {
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
            ] => new UserHasNoPermissionToUploadFilesErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new MediaItemSourceIsMissingErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E4,
            ] => new UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E5,
            ] => new UserDoesNotExistErrorPayload(
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
