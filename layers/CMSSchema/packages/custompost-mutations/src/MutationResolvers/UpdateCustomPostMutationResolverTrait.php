<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

trait UpdateCustomPostMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataProvider): mixed
    {
        return $this->update($fieldDataProvider);
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exists)
     */
    abstract protected function update(FieldDataAccessorInterface $fieldDataProvider): string | int;

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateErrors(FieldDataAccessorInterface $fieldDataProvider): array
    {
        return $this->validateUpdateErrors($fieldDataProvider);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    abstract protected function validateUpdateErrors(FieldDataAccessorInterface $fieldDataProvider): array;
}
