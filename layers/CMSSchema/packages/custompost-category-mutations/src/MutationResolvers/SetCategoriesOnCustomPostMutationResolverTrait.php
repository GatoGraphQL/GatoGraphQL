<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

trait SetCategoriesOnCustomPostMutationResolverTrait
{
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
}
