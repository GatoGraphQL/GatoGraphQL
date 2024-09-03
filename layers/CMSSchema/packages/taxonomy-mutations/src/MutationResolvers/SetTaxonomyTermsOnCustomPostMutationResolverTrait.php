<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\Facades\TaxonomyTermTypeAPIFacade;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait SetTaxonomyTermsOnCustomPostMutationResolverTrait
{
    /**
     * Retrieve the taxonomy from the queried entites.
     * If the taxonomy is explicitly provided, validate that the
     * entities indeed have that taxonomy.
     */
    protected function getTaxonomyToTaxonomyTermIDs(
        array $taxonomyTermIDs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        /** @var string|null */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        if ($taxonomyName !== null) {
            $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

            foreach ($taxonomyTermIDs as $taxonomyTermID) {
                $this->validateTaxonomyTermByIDExists(
                    $taxonomyTermID,
                    $taxonomyName,
                    $fieldDataAccessor,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            }

            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                return null;
            }

            return [
                $taxonomyName => $taxonomyTermIDs,
            ];
        }

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        // Retrieve the taxonomy from the terms
        $taxonomyToTaxonomyTermsIDs = [];
        $taxonomyTermTypeAPI = $this->getTaxonomyTermTypeAPI();
        foreach ($taxonomyTermIDs as $taxonomyTermID) {
            $taxonomyName = $taxonomyTermTypeAPI->getTaxonomyTermTaxonomy($taxonomyTermID);
            if ($taxonomyName === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E6,
                            [
                                $taxonomyTermID,
                            ]
                        ),
                        $fieldDataAccessor->getField(),
                    )
                );
                continue;
            }
            $taxonomyToTaxonomyTermsIDs[$taxonomyName] ??= [];
            $taxonomyToTaxonomyTermsIDs[$taxonomyName][] = $taxonomyTermID;
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        return $taxonomyToTaxonomyTermsIDs;
    }

    /**
     * Retrieve the taxonomy from the queried entites.
     * If the taxonomy is explicitly provided, validate that the
     * entities indeed have that taxonomy.
     */
    protected function getTaxonomyNameByTaxonomyTermSlugs(
        array $taxonomyTermSlugs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        $taxonomyTermTypeAPI = $this->getTaxonomyTermTypeAPI();

        /** @var string|null */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        if ($taxonomyName !== null) {

        }

        return [];
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

    abstract protected function validateTaxonomyTermByIDExists(
        string|int $taxonomyTermID,
        string|null $taxonomyName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    );

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
