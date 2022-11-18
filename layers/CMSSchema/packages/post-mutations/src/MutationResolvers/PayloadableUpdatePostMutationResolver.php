<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\PostMutations\ObjectModels\PostCRUDMutationPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayload;

class PayloadableUpdatePostMutationResolver extends UpdatePostMutationResolver
{
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
     * Catch the CRUD exception, and return it in the Mutation Payload
     * 
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        try {
            $customPostID = parent::executeMutation(
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
            $payload = new PostCRUDMutationPayload(
                OperationStatusEnum::SUCCESS,
                $customPostID,
                null,
            );
        } catch (CustomPostCRUDMutationException $customPostCRUDMutationException) {
            $errors = [
                new ErrorPayload(
                    $customPostCRUDMutationException->getMessage(),
                    $customPostCRUDMutationException->getErrorCode(),
                    $customPostCRUDMutationException->getData(),
                ),
            ];
            $payload = new PostCRUDMutationPayload(
                OperationStatusEnum::FAILURE,
                null,
                $errors,
            );
        }
        $this->getObjectDictionary()->set(PostCRUDMutationPayload::class, $payload->getID(), $payload);
        return $payload->getID();
    }
}
