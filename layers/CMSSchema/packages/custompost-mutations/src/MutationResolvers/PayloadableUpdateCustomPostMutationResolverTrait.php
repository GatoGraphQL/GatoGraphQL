<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

trait PayloadableUpdateCustomPostMutationResolverTrait
{
    use PayloadableMutationResolverTrait;    
    use UpdateCustomPostMutationResolverTrait {
        UpdateCustomPostMutationResolverTrait::executeMutation as upstreamExecuteMutation;
    }
    use PayloadableCreateOrUpdateCustomPostMutationResolverTrait;
    
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
            return $this->createFailurePayloadMutation(
                $this->createErrorPayloadsFromObjectTypeFieldResolutionFeedbacks(
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                )
            );
        }

        $customPostID = null;
        try {
            /** @var string|int */
            $customPostID = $this->upstreamExecuteMutation(
                $fieldDataAccessor,
                $separateObjectTypeFieldResolutionFeedbackStore,
            );
        } catch (CustomPostCRUDMutationException $customPostCRUDMutationException) {
            return $this->createFailurePayloadMutation(
                [
                    new GenericErrorPayload(
                        $customPostCRUDMutationException->getMessage(),
                        $customPostCRUDMutationException->getErrorCode(),
                        $customPostCRUDMutationException->getData(),
                    ),
                ]
            );
        }

        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createFailurePayloadMutation(
                $this->createErrorPayloadsFromObjectTypeFieldResolutionFeedbacks(
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                )
            );
        }

        /** @var string|int $customPostID */
        return $this->createSuccessPayloadMutation($customPostID);
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
