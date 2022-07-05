<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait CreateCustomPostMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        return $this->create($fieldDataAccessor);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    abstract protected function create(FieldDataAccessorInterface $fieldDataAccessor): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return $this->validateCreateErrors($fieldDataAccessor);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateCreateErrors(FieldDataAccessorInterface $fieldDataAccessor): array;
}
