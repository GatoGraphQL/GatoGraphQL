<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\MutateTermMetaMutationResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\MutateTaxonomyTermMutationResolverTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait MutateTaxonomyTermMetaMutationResolverTrait
{
    use MutateTaxonomyTermMutationResolverTrait;
    use MutateTermMetaMutationResolverTrait;

    abstract protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface;
    
    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getTaxonomyMetaTypeAPI();
    }

    protected function validateSingleMetaEntryDoesNotExist(
        string|int $taxonomyTermID,
        string $key,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta($taxonomyTermID, $key, true) === null) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getSingleMetaEntryAlreadyExistsError($taxonomyTermID, $key),
                $fieldDataAccessor->getField(),
            )
        );
    }
}
