<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermMetaCRUDMutationException;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\TaxonomyMetaMutations\Constants\MutationInputProperties;

trait PayloadableDeleteCategoryTermMetaMutationResolverTrait
{
    use PayloadableMutationResolverTrait, DeleteCategoryTermMetaMutationResolverTrait {
        DeleteCategoryTermMetaMutationResolverTrait::executeMutation as upstreamExecuteMutation;
        PayloadableMutationResolverTrait::validate insteadof DeleteCategoryTermMetaMutationResolverTrait;
    }
    use PayloadableCategoryMetaMutationResolverTrait;

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
        $this->validateDeleteMetaErrors($fieldDataAccessor, $separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createFailureObjectMutationPayload(
                array_map(
                    $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                )
            )->getID();
        }

        /** @var string|int */
        $categoryTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        try {
            /** @var bool */
            $operationSuccessful = $this->upstreamExecuteMutation(
                $fieldDataAccessor,
                $separateObjectTypeFieldResolutionFeedbackStore,
            );
        } catch (CategoryTermMetaCRUDMutationException $categoryTermMetaCRUDMutationException) {
            return $this->createFailureObjectMutationPayload(
                [
                    $this->createGenericErrorPayloadFromPayloadClientException($categoryTermMetaCRUDMutationException),
                ]
            )->getID();
        }

        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createFailureObjectMutationPayload(
                array_map(
                    $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                ),
                $categoryTermID
            )->getID();
        }

        return $this->createSuccessObjectMutationPayload($categoryTermID)->getID();
    }
}
