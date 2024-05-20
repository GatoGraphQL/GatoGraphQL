<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableUpdateCustomPostMutationResolverTrait;
use PoPCMSSchema\PageMutations\Constants\HookNames;
use PoPCMSSchema\PageMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoEditingPageCapabilityErrorPayload;
use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoPublishingPageCapabilityErrorPayload;
use PoPCMSSchema\PageMutations\ObjectModels\PageDoesNotExistErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadablePageMutationResolverTrait
{
    use PayloadableUpdateCustomPostMutationResolverTrait {
        createErrorPayloadFromObjectTypeFieldResolutionFeedback as upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback;
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
            ] => new LoggedInUserHasNoEditingPageCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new LoggedInUserHasNoPublishingPageCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new PageDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => App::applyFilters(
                HookNames::ERROR_PAYLOAD,
                $this->upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback),
                $feedbackItemResolution,
            )
        };
    }
}
