<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\UserMutations\Exception\UserCRUDMutationException;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

trait DeleteUserMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        return $this->delete(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return bool Whether the user was deleted
     * @throws UserCRUDMutationException If there was an error (eg: User does not exist)
     */
    abstract protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool;

    /**
     * Validate the app-level errors in top-level "errors" entry.
     */
    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->validateDeleteErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    abstract protected function validateDeleteErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
}
