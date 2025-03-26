<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostCategoryMetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers\MutateTaxonomyTermMetaMutationResolverTrait;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait SetMetaOnCategoryMutationResolverTrait
{
    use MutateTaxonomyTermMetaMutationResolverTrait;

    protected function validateSetMetaOnCategory(
        string $customPostType,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // @todo Do something
    }

    protected function setMetaOnCategory(
        string|int $customPostID,
        bool $append,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // @todo Do something
    }

    public function createSetMetaOnCategoryErrorPayloadFromObjectTypeFieldResolutionFeedback(
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
            default => null,
        };
    }
}
