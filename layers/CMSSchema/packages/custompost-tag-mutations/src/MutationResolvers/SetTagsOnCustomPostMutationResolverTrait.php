<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

trait SetTagsOnCustomPostMutationResolverTrait
{
    /**
     * @param array<string|int> $customPostTagIDs
     */
    protected function validateTagsByIDExist(
        string $taxonomyName,
        array $customPostTagIDs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $query = [
            'taxonomy' => $taxonomyName,
            'include' => $customPostTagIDs,
        ];
        $existingTagIDs = $this->getTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        $nonExistingTagIDs = array_values(array_diff(
            $customPostTagIDs,
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
     * @param array<string> $customPostTagSlugs
     */
    protected function validateTagsBySlugExist(
        string $taxonomyName,
        array $customPostTagSlugs,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $query = [
            'taxonomy' => $taxonomyName,
            'slugs' => $customPostTagSlugs,
        ];
        $existingTagSlugs = $this->getTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::SLUGS]);
        $nonExistingTagSlugs = array_values(array_diff(
            $customPostTagSlugs,
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
}
