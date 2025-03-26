<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;

trait SetTaxonomyTermMetaMutationResolverTrait
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        return $this->setMeta($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * @return string|int The ID of the taxonomy term
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    abstract protected function setMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int;

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->validateSetMetaErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    abstract protected function validateSetMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
}
