<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoPCMSSchema\Taxonomies\Facades\TaxonomyTermTypeAPIFacade;

trait SetTaxonomyTermsOnCustomPostMutationResolverTrait
{
    /**
     * Retrieve the taxonomy from the queried object's CPT,
     * which works as long as it has only 1 tag taxonomy registered.
     */
    protected function getTaxonomyName(
        bool $hierarchical,
        int|string $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?string {
        $customPostType = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        if ($customPostType === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E11,
                        [
                            $customPostID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return null;
        }

        $taxonomyTermTypeAPI = $this->getTaxonomyTermTypeAPI();
        $customPostTypeTaxonomyNames = $taxonomyTermTypeAPI->getCustomPostTypeTaxonomyNames($customPostType);
        $taxonomyNames = array_values(array_filter(
            $customPostTypeTaxonomyNames,
            fn (string $taxonomyName) => $hierarchical
                ? $taxonomyTermTypeAPI->isTaxonomyHierarchical($taxonomyName)
                : !$taxonomyTermTypeAPI->isTaxonomyHierarchical($taxonomyName),
        ));

        if (count($taxonomyNames) === 0) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getNoTaxonomiesRegisteredInCustomPostTypeFeedbackItemResolution($customPostType),
                    $fieldDataAccessor->getField(),
                )
            );
            return null;
        }

        return $taxonomyNames[0];
    }

    protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        return TaxonomyTermTypeAPIFacade::getInstance();
    }

    abstract protected function getNoTaxonomiesRegisteredInCustomPostTypeFeedbackItemResolution(string $customPostType): FeedbackItemResolution;

    /**
     * Retrieve the taxonomy passed via the `taxonomy` input.
     * If that's not possible (eg: on `createCustomPost:input.categoriesBy`),
     * then retrieve it from queried object's CPT.
     */
    protected function validateTaxonomyIsRegisteredForCustomPost(
        string|int $customPostID,
        string $taxonomName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $customPostType = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        if ($customPostType === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E11,
                        [
                            $customPostID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return null;
        }

        $taxonomyNames = $this->getTaxonomyTermTypeAPI()->getCustomPostTypeTaxonomyNames($customPostType);
        if (in_array($taxonomName, $taxonomyNames)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E12,
                    [
                        $taxonomName,
                        $customPostType,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }
}
