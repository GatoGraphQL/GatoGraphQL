<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\SetTaxonomyTermsOnCustomPostMutationResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

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

    protected function getNoTaxonomiesRegisteredInCustomPostTypeFeedbackItemResolution(string $customPostType): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E4,
            [
                $customPostType,
            ]
        );
    }

    /**
     * @param string[] $taxonomyNames
     */
    protected function getMultipleTaxonomiesRegisteredInCustomPostTypeFeedbackItemResolution(
        string $customPostType,
        array $taxonomyNames
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E5,
            [
                $customPostType,
                implode($this->__('\', \''), $taxonomyNames)
            ]
        );
    }
}
