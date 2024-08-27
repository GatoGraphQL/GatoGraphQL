<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableSetTagsOnCustomPostMutationResolverTrait;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

class PayloadableSetTagsOnCustomPostMutationResolver extends SetTagsOnCustomPostMutationResolver
{
    use PayloadableMutationResolverTrait;
    use PayloadableSetTagsOnCustomPostMutationResolverTrait;

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

        /** @var string|int */
        $postID = parent::executeMutation(
            $fieldDataAccessor,
            $separateObjectTypeFieldResolutionFeedbackStore,
        );

        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createFailureObjectMutationPayload(
                array_map(
                    $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                ),
                $postID
            )->getID();
        }

        /** @var string|int $postID */
        return $this->createSuccessObjectMutationPayload($postID)->getID();
    }
}
