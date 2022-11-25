<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CommentMutations\ObjectModels\CommentAuthorEmailIsMissingErrorPayload;
use PoPCMSSchema\CommentMutations\ObjectModels\CommentAuthorNameIsMissingErrorPayload;
use PoPCMSSchema\CommentMutations\ObjectModels\CommentParentCommentDoesNotExistErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

class PayloadableAddCommentToCustomPostMutationResolver extends AddCommentToCustomPostMutationResolver
{
    use PayloadableMutationResolverTrait;

    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        /** @var ObjectDictionaryInterface */
        return $this->objectDictionary ??= $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
    }

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
        parent::validateErrors($fieldDataAccessor, $separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createAndStoreFailureObjectMutationPayload(
                array_map(
                    $this->createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
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
            return $this->createAndStoreFailureObjectMutationPayload(
                [
                    $this->createAndStoreGenericErrorPayloadFromPayloadClientException($commentCRUDMutationException),
                ]
            )->getID();
        }

        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createAndStoreFailureObjectMutationPayload(
                array_map(
                    $this->createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                ),
                $commentID
            )->getID();
        }

        /** @var string|int $commentID */
        return $this->createAndStoreSuccessObjectMutationPayload($commentID)->getID();
    }

    /**
     * Override: Do nothing, because the app-level errors are
     * returned in the Payload, not in top-level "errors" entry.
     */
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
    }

    protected function createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorPayload = $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback);
        $this->getObjectDictionary()->set(
            get_class($errorPayload),
            $errorPayload->getID(),
            $errorPayload,
        );
        return $errorPayload;
    }

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorFeedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match ([$errorFeedbackItemResolution->getFeedbackProviderServiceClass(), $errorFeedbackItemResolution->getCode()]) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
            ] => new UserIsNotLoggedInErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new CommentAuthorNameIsMissingErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new CommentAuthorEmailIsMissingErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E6,
            ] => new CommentParentCommentDoesNotExistErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
                $errorFeedbackItemResolution->getNamespacedCode(),
            ),
        };
    }
}
