<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\ObjectModels\CategoryTermDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostCategoryMetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider as TaxonomyMetaMutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers\SetTaxonomyTermsOnCustomPostMutationResolverTrait;
use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload;
use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\TaxonomyIsNotValidErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

trait SetCategoriesOnCustomPostMutationResolverTrait
{
    use SetTaxonomyTermsOnCustomPostMutationResolverTrait;

    /**
     * @param array<string|int> $taxonomyTermIDs
     */
    protected function getTaxonomyIsNotRegisteredInCustomPostTypeFeedbackItemResolution(
        string $customPostType,
        string $taxonomyName,
        array $taxonomyTermIDs,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E4,
            [
                $taxonomyName,
                implode('\', \'', $taxonomyTermIDs),
                $customPostType,
            ]
        );
    }

    protected function validateSetCategoriesOnCustomPost(
        string $customPostType,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var stdClass|null */
        $categoriesBy = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORY_BY);
        if ($categoriesBy === null || ((array) $categoriesBy) === []) {
            return;
        }

        // If `null` there was an error (already added to FeedbackStore)
        $categoryTaxonomyToTaxonomyTerms = $this->getCategoryTaxonomyToTaxonomyTerms($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($categoryTaxonomyToTaxonomyTerms === null) {
            return;
        }

        foreach ($categoryTaxonomyToTaxonomyTerms as $taxonomyName => $categoryIDs) {
            $this->validateCanLoggedInUserAssignTermsToTaxonomy(
                $taxonomyName,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );

            $this->validateTaxonomyIsRegisteredForCustomPostType(
                $customPostType,
                $taxonomyName,
                $categoryIDs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    protected function setCategoriesOnCustomPost(
        string|int $customPostID,
        bool $append,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var array<string,array<string|int>> */
        $categoryTaxonomyToTaxonomyTerms = $this->getCategoryTaxonomyToTaxonomyTerms($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        foreach ($categoryTaxonomyToTaxonomyTerms as $taxonomyName => $categoryIDs) {
            $this->getCustomPostCategoryMetaTypeMutationAPI()->setCategoriesByID(
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
        $categoriesBy = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORY_BY);
        if (isset($categoriesBy->{MutationInputProperties::ID})) {
            $categoryIDs = $categoriesBy->{MutationInputProperties::ID};
            return $this->getTaxonomyToTaxonomyTermsByID(
                true,
                $categoryIDs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        if (isset($categoriesBy->{MutationInputProperties::SLUG})) {
            $categorySlugs = $categoriesBy->{MutationInputProperties::SLUG};
            return $this->getTaxonomyToTaxonomyTermsBySlug(
                true,
                $categorySlugs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return null;
    }

    public function createSetCategoriesOnCustomPostErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
    ): ?ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match (
            [
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode()
            ]
        ) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
            ] => new UserIsNotLoggedInErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E4,
            ] => new TaxonomyIsNotValidErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E6,
            ] => new CategoryTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new CategoryTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E8,
            ] => new CategoryTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E9,
            ] => new CategoryTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMetaMutationErrorFeedbackItemProvider::class,
                TaxonomyMetaMutationErrorFeedbackItemProvider::E10,
            ] => new LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMetaMutationErrorFeedbackItemProvider::class,
                TaxonomyMetaMutationErrorFeedbackItemProvider::E11,
            ] => new TaxonomyIsNotValidErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMetaMutationErrorFeedbackItemProvider::class,
                TaxonomyMetaMutationErrorFeedbackItemProvider::E12,
            ] => new TaxonomyIsNotValidErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => null,
        };
    }

    protected function getTaxonomyTermDoesNotExistError(
        ?string $taxonomyName,
        string|int $taxonomyTermID,
    ): FeedbackItemResolution {
        if ($taxonomyName !== null && $taxonomyName !== '') {
            return new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
                [
                    $taxonomyName,
                    $taxonomyTermID,
                ]
            );
        }
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E6,
            [
                $taxonomyTermID,
            ]
        );
    }

    protected function getTaxonomyTermBySlugDoesNotExistError(
        ?string $taxonomyName,
        string|int $taxonomyTermSlug,
    ): FeedbackItemResolution {
        if ($taxonomyName !== null && $taxonomyName !== '') {
            return new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E9,
                [
                    $taxonomyName,
                    $taxonomyTermSlug,
                ]
            );
        }
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E8,
            [
                $taxonomyTermSlug,
            ]
        );
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }
}
