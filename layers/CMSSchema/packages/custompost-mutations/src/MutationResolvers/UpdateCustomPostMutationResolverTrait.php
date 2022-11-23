<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;

trait UpdateCustomPostMutationResolverTrait
{
    /**
     * if isPayloadable(): Validate the app-level errors here.
     * Otherwise, it will be done in the top-level errors entry.
     *
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        if ($this->isPayloadable()) {
            $this->validateUpdateErrors($fieldDataAccessor, $separateObjectTypeFieldResolutionFeedbackStore);
            if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return $this->createAndStoreErrorPayloadsFromObjectTypeFieldResolutionFeedbacks($separateObjectTypeFieldResolutionFeedbackStore->getErrors());
            }
            $result = $this->update(
                $fieldDataAccessor,
                $separateObjectTypeFieldResolutionFeedbackStore,
            );
            if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return $this->createAndStoreErrorPayloadsFromObjectTypeFieldResolutionFeedbacks($separateObjectTypeFieldResolutionFeedbackStore->getErrors());
            }    
            return $result;
        }
        return $this->update(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $objectTypeFieldResolutionFeedbacks
     */
    protected function createAndStoreErrorPayloadsFromObjectTypeFieldResolutionFeedbacks(
        array $objectTypeFieldResolutionFeedbacks
    ): mixed {
        $errorPayloadIDs = [];
        foreach ($objectTypeFieldResolutionFeedbacks as $errorObjectTypeFieldResolutionFeedback) {
            $errorFeedbackItemResolution = $errorObjectTypeFieldResolutionFeedback->getFeedbackItemResolution();
            /** @var ErrorPayloadInterface */
            $errorPayload = match ([$errorFeedbackItemResolution->getFeedbackProviderServiceClass(), $errorFeedbackItemResolution->getCode()]) {
                [
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E7,
                ] => new CustomPostDoesNotExistErrorPayload(
                    $errorFeedbackItemResolution->getMessage(),
                    $errorFeedbackItemResolution->getCode(),
                ),
                [
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E8,
                ] => new LoggedInUserHasNoPermissionToEditCustomPostErrorPayload(
                    $errorFeedbackItemResolution->getMessage(),
                    $errorFeedbackItemResolution->getCode(),
                ),
                default => new GenericErrorPayload(
                    $errorFeedbackItemResolution->getMessage(),
                    $errorFeedbackItemResolution->getCode(),
                ),
            };
            $errorPayloadIDs[] = $errorPayload->getID();
        }
        return $errorPayloadIDs;
    }

    protected function isPayloadable(): bool
    {
        return false;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    abstract protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int;

    /**
     * if !isPayloadable(): Validate the app-level errors
     * in top-level "errors" entry.
     *
     * Otherwise, it will be done in executeMutation,
     * and returned via the Payload.
     */
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->isPayloadable()) {
            return;
        }
        $this->validateUpdateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    abstract protected function validateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
}
