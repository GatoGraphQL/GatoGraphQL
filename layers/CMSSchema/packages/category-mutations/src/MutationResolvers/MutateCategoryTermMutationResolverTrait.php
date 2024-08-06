<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

trait MutateCategoryTermMutationResolverTrait
{
    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function getTaxonomyDoesNotExistError(
        string $taxonomyName,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E5,
            [
                $taxonomyName,
            ]
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
