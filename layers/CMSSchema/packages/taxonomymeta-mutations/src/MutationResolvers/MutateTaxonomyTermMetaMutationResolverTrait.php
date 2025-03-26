<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\MutateTaxonomyTermMutationResolverTrait;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait MutateTaxonomyTermMetaMutationResolverTrait
{
    use MutateTaxonomyTermMutationResolverTrait;

    abstract protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface;

    protected function validateSingleMetaEntryDoesNotExist(
        string|int $taxonomyTermID,
        string $key,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Validate user permission
        // @todo Validate single meta does not exist
        // if ($this->getTaxonomyTermTypeAPI()->canUserEditTaxonomy($userID, $taxonomyName)) {
        //     return;
        // }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getSingleMetaEntryAlreadyExistsError($taxonomyTermID, $key),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function getSingleMetaEntryAlreadyExistsError(
        string|int $taxonomyTermID,
        string $key,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
            [
                $taxonomyTermID,
                $key,
            ]
        );
    }
}
