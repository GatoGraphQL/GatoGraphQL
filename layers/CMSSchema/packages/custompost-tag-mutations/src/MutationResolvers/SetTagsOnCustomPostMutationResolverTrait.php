<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\SetTaxonomyTermsOnCustomPostMutationResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

trait SetTagsOnCustomPostMutationResolverTrait
{
    use SetTaxonomyTermsOnCustomPostMutationResolverTrait;

    /**
     * @param array<string|int> $tagIDs
     */
    protected function validateTagsByIDExist(
        string $taxonomyName,
        array $tagIDs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $query = [
            'taxonomy' => $taxonomyName,
            'include' => $tagIDs,
        ];
        $existingTagIDs = $this->getTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        $nonExistingTagIDs = array_values(array_diff(
            $tagIDs,
            $existingTagIDs
        ));
        if ($nonExistingTagIDs !== []) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E2,
                        [
                            implode(
                                $this->__('\', \'', 'custompost-tag-mutations'),
                                $nonExistingTagIDs
                            ),
                            $taxonomyName,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    abstract protected function getTagTypeAPI(): TagTypeAPIInterface;

    /**
     * @param array<string> $tagSlugs
     */
    protected function validateTagsBySlugExist(
        string $taxonomyName,
        array $tagSlugs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $query = [
            'taxonomy' => $taxonomyName,
            'slugs' => $tagSlugs,
        ];
        $existingTagSlugs = $this->getTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::SLUGS]);
        $nonExistingTagSlugs = array_values(array_diff(
            $tagSlugs,
            $existingTagSlugs
        ));
        if ($nonExistingTagSlugs !== []) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E3,
                        [
                            implode(
                                $this->__('\', \'', 'custompost-tag-mutations'),
                                $nonExistingTagSlugs
                            ),
                            $taxonomyName,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

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

    protected function setTagsOnCustomPostOrAddError(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var stdClass|null */
        $tagsBy = $fieldDataAccessor->getValue(MutationInputProperties::TAGS_BY);
        if ($tagsBy === null || ((array) $tagsBy) === []) {
            return;
        }

        // If `null` there was an error (already added to FeedbackStore)
        $tagTaxonomyToTaxonomyTerms = $this->getTagTaxonomyToTaxonomyTerms($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($tagTaxonomyToTaxonomyTerms === null) {
            return;
        }

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        foreach ($tagTaxonomyToTaxonomyTerms as $taxonomyName => $tagIDs) {
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
        foreach ($tagTaxonomyToTaxonomyTerms as $taxonomyName => $tagIDs) {
            $this->getCustomPostTagTypeMutationAPI()->setTagsByID(
                $taxonomyName,
                $customPostID,
                $tagIDs,
                $append
            );
        }
    }

    /**
     * Retrieve the taxonomy from the queried object's CPT,
     * which works as long as it has only 1 tag taxonomy registered.
     *
     * @return array<string,array<string|int>>|null
     */
    protected function getTagTaxonomyToTaxonomyTerms(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        $tagsBy = $fieldDataAccessor->getValue(MutationInputProperties::TAGS_BY);
        if (isset($tagsBy->{MutationInputProperties::IDS})) {
            $categoryIDs = $tagsBy->{MutationInputProperties::IDS};
            return $this->getTaxonomyToTaxonomyTermsByID(
                $categoryIDs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        if (isset($tagsBy->{MutationInputProperties::SLUGS})) {
            $categorySlugs = $tagsBy->{MutationInputProperties::SLUGS};
            return $this->getTaxonomyToTaxonomyTermsBySlug(
                $categorySlugs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return null;
    }
}
