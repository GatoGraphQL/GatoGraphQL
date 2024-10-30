<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\TagMutations\ObjectModels\TagTermDoesNotExistErrorPayload;
use PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider as TaxonomyMutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\SetTaxonomyTermsOnCustomPostMutationResolverTrait;
use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload;
use PoPCMSSchema\TaxonomyMutations\ObjectModels\TaxonomyIsNotValidErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

trait SetTagsOnCustomPostMutationResolverTrait
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

    protected function validateSetTagsOnCustomPost(
        string $customPostType,
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

        foreach ($tagTaxonomyToTaxonomyTerms as $taxonomyName => $tagIDs) {
            $this->validateCanLoggedInUserAssignTermsToTaxonomy(
                $taxonomyName,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );

            $this->validateTaxonomyIsRegisteredForCustomPostType(
                $customPostType,
                $taxonomyName,
                $tagIDs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    protected function setTagsOnCustomPost(
        string|int $customPostID,
        bool $append,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var array<string,array<string|int>> */
        $tagTaxonomyToTaxonomyTerms = $this->getTagTaxonomyToTaxonomyTerms($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
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
            $tagIDs = $tagsBy->{MutationInputProperties::IDS};
            return $this->getTaxonomyToTaxonomyTermsByID(
                false,
                $tagIDs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        if (isset($tagsBy->{MutationInputProperties::SLUGS})) {
            $tagSlugs = $tagsBy->{MutationInputProperties::SLUGS};
            return $this->getTaxonomyToTaxonomyTermsBySlug(
                false,
                $tagSlugs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return null;
    }

    protected function createSetTagsOnCustomPostErrorPayloadFromObjectTypeFieldResolutionFeedback(
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
            ] => new TagTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new TagTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E8,
            ] => new TagTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E9,
            ] => new TagTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMutationErrorFeedbackItemProvider::class,
                TaxonomyMutationErrorFeedbackItemProvider::E10,
            ] => new LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMutationErrorFeedbackItemProvider::class,
                TaxonomyMutationErrorFeedbackItemProvider::E11,
            ] => new TaxonomyIsNotValidErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMutationErrorFeedbackItemProvider::class,
                TaxonomyMutationErrorFeedbackItemProvider::E12,
            ] => new TaxonomyIsNotValidErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => null,
        };
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
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
}
