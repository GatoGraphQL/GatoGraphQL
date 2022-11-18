<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

class PayloadableUpdatePostMutationResolver extends UpdatePostMutationResolver
{
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
        } catch (CustomPostCRUDMutationException $customPostCRUDMutationException) {

        }
    }
}
