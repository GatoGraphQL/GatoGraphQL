<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CategoryMutations\Exception\CategoryCRUDMutationException;

trait CreateCategoryMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        return $this->create($fieldDataAccessor);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CategoryCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    abstract protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int;

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->validateCreateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    abstract protected function validateCreateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
}
