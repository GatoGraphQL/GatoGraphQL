<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\TagMutations\ObjectModels\TagTermDoesNotExistErrorPayload;
use PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider as TaxonomyMutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableSetTagsOnCustomPostMutationResolverTrait
{
    use PayloadableCustomPostMutationResolverTrait {
        PayloadableCustomPostMutationResolverTrait::createErrorPayloadFromObjectTypeFieldResolutionFeedback as upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback;
    }

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match (
            [
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode()
            ]
        ) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new TagTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new TagTermDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMutationErrorFeedbackItemProvider::class,
                TaxonomyMutationErrorFeedbackItemProvider::E10,
            ] => new LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => $this->upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback
            ),
        };
    }

    protected function getUserNotLoggedInErrorFeedbackItemProviderClass(): string
    {
        return MutationErrorFeedbackItemProvider::class;
    }

    protected function getUserNotLoggedInErrorFeedbackItemProviderCode(): string
    {
        return MutationErrorFeedbackItemProvider::E1;
    }
}
