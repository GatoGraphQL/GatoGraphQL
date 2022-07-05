<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait UpdateCustomPostMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        return $this->update($fieldDataAccessor);
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exists)
     */
    abstract protected function update(FieldDataAccessorInterface $fieldDataAccessor): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return $this->validateUpdateErrors($fieldDataAccessor);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateUpdateErrors(FieldDataAccessorInterface $fieldDataAccessor): array;
}
