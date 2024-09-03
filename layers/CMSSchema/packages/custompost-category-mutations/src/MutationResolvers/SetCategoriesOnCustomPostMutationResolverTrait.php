<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\SetTaxonomyTermsOnCustomPostMutationResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

trait SetCategoriesOnCustomPostMutationResolverTrait
{
    use SetTaxonomyTermsOnCustomPostMutationResolverTrait;

    /**
     * @param array<string|int> $customPostCategoryIDs
     */
    protected function validateCategoriesByIDExist(
        string $taxonomyName,
        array $customPostCategoryIDs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $query = [
            'taxonomy' => $taxonomyName,
            'include' => $customPostCategoryIDs,
        ];
        $existingCategoryIDs = $this->getCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        $nonExistingCategoryIDs = array_values(array_diff(
            $customPostCategoryIDs,
            $existingCategoryIDs
        ));
        if ($nonExistingCategoryIDs !== []) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E2,
                        [
                            implode(
                                $this->__('\', \'', 'custompost-category-mutations'),
                                $nonExistingCategoryIDs
                            ),
                            $taxonomyName,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    /**
     * @param array<string|int> $customPostCategorySlugs
     */
    protected function validateCategoriesBySlugExist(
        string $taxonomyName,
        array $customPostCategorySlugs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $query = [
            'taxonomy' => $taxonomyName,
            'slugs' => $customPostCategorySlugs,
        ];
        $existingCategorySlugs = $this->getCategoryTypeAPI()->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::SLUGS]);
        $nonExistingCategorySlugs = array_values(array_diff(
            $customPostCategorySlugs,
            $existingCategorySlugs
        ));
        if ($nonExistingCategorySlugs !== []) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E3,
                        [
                            implode(
                                $this->__('\', \'', 'custompost-category-mutations'),
                                $nonExistingCategorySlugs
                            ),
                            $taxonomyName,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    abstract protected function getCategoryTypeAPI(): CategoryTypeAPIInterface;

    protected function getTaxonomyIsNotRegisteredInCustomPostTypeFeedbackItemResolution(
        string $taxonomyName,
        string $customPostType,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E4,
            [
                $taxonomyName,
                $customPostType,
            ]
        );
    }

    protected function setCategoriesOnCustomPostOrAddError(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var stdClass|null */
        $categoriesBy = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES_BY);
        if ($categoriesBy === null || ((array) $categoriesBy) === []) {
            return;
        }

        // If `null` there was an error (already added to FeedbackStore)
        $categoryTaxonomyToTaxonomyTerms = $this->getCategoryTaxonomyToTaxonomyTerms($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($categoryTaxonomyToTaxonomyTerms === null) {
            return;
        }

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        foreach ($categoryTaxonomyToTaxonomyTerms as $taxonomyName => $categoryIDs) {
            $this->validateCanLoggedInUserAssignTermsToTaxonomy(
                $taxonomyName,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );

            $this->validateTaxonomyIsRegisteredForCustomPost(
                $customPostID,
                $taxonomyName,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $append = $fieldDataAccessor->getValue(MutationInputProperties::APPEND);
        foreach ($categoryTaxonomyToTaxonomyTerms as $taxonomyName => $categoryIDs) {
            $this->getCustomPostCategoryTypeMutationAPI()->setCategoriesByID(
                $taxonomyName,
                $customPostID,
                $categoryIDs,
                $append
            );
        }
    }

    /**
     * Retrieve the taxonomy from the queried object's CPT,
     * which works as long as it has only 1 category taxonomy registered.
     *
     * @return array<string,array<string|int>>|null
     */
     protected function getCategoryTaxonomyToTaxonomyTerms(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        $categoriesBy = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES_BY);
        if (isset($categoriesBy->{MutationInputProperties::IDS})) {
            $categoryIDs = $categoriesBy->{MutationInputProperties::IDS};
            return $this->getTaxonomyToTaxonomyTermsByID(
                $categoryIDs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        if (isset($categoriesBy->{MutationInputProperties::SLUGS})) {
            $categorySlugs = $categoriesBy->{MutationInputProperties::SLUGS};
            return $this->getTaxonomyToTaxonomyTermsBySlug(
                $categorySlugs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return null;
    }
}
