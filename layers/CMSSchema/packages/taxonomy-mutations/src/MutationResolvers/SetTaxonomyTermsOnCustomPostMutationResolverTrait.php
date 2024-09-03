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

        return $taxonomyNames[0];
    }

    protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        return TaxonomyTermTypeAPIFacade::getInstance();
    }

    /**
     * Retrieve the taxonomy passed via the `taxonomy` input.
     * If that's not possible (eg: on `createCustomPost:input.categoriesBy`),
     * then retrieve it from queried object's CPT.
     */
    protected function validateTaxonomyIsRegisteredForCustomPost(
        string|int $customPostID,
        string $taxonomyName,
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
        if (in_array($taxonomyName, $taxonomyNames)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getTaxonomyIsNotRegisteredInCustomPostTypeFeedbackItemResolution(
                    $taxonomyName,
                    $customPostType,
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function getTaxonomyIsNotRegisteredInCustomPostTypeFeedbackItemResolution(
        string $taxonomyName,
        string $customPostType,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E12,
            [
                $taxonomyName,
                $customPostType,
            ]
        );
    }
}
