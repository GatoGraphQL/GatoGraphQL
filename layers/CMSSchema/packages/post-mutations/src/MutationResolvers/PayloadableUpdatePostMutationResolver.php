<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

class PayloadableUpdatePostMutationResolver extends UpdatePostMutationResolver
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
     * Catch the CRUD exception, and return it in the Mutation Payload
     * 
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPostID = null;
        try {
            /** @var string|int */
            $customPostID = parent::executeMutation(
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } catch (CustomPostCRUDMutationException $customPostCRUDMutationException) {
            return $this->createFailurePayloadMutation($customPostCRUDMutationException);
        }
        /** @var string|int $customPostID */
        return $this->createSuccessPayloadMutation($customPostID);
    }
}
