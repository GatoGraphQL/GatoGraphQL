<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

trait PayloadableUpdateCustomPostMutationResolverTrait
{
    use UpdateCustomPostMutationResolverTrait {
        UpdateCustomPostMutationResolverTrait::executeMutation as upstreamExecuteMutation;
    }
    
    /**
     * Validate the app-level errors here, return them in the Payload.
     *
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $this->validateUpdateErrors($fieldDataAccessor, $separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createAndStoreErrorPayloadsFromObjectTypeFieldResolutionFeedbacks($separateObjectTypeFieldResolutionFeedbackStore->getErrors());
        }
        $result = $this->upstreamExecuteMutation(
            $fieldDataAccessor,
            $separateObjectTypeFieldResolutionFeedbackStore,
        );
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createAndStoreErrorPayloadsFromObjectTypeFieldResolutionFeedbacks($separateObjectTypeFieldResolutionFeedbackStore->getErrors());
        }    
        return $result;
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $objectTypeFieldResolutionFeedbacks
     */
    protected function createAndStoreErrorPayloadsFromObjectTypeFieldResolutionFeedbacks(
        array $objectTypeFieldResolutionFeedbacks
    ): mixed {
        $errorPayloadIDs = [];
        foreach ($objectTypeFieldResolutionFeedbacks as $objectTypeFieldResolutionFeedback) {
            $errorPayload = $this->createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback);
            $errorPayloadIDs[] = $errorPayload->getID();
        }
        return $errorPayloadIDs;
    }

    protected function createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorFeedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        /** @var ErrorPayloadInterface */
        return match ([$errorFeedbackItemResolution->getFeedbackProviderServiceClass(), $errorFeedbackItemResolution->getCode()]) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new CustomPostDoesNotExistErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E8,
            ] => new LoggedInUserHasNoPermissionToEditCustomPostErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
                $errorFeedbackItemResolution->getCode(),
            ),
        };
    }

    /**
     * Do nothing
     */
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
    }
}
