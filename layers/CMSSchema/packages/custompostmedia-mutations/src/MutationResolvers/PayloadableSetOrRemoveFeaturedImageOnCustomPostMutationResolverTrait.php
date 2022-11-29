<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMediaMutations\ObjectModels\MediaItemDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider as CustomPostsMutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableSetOrRemoveFeaturedImageOnCustomPostMutationResolverTrait
{
    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorFeedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match ([$errorFeedbackItemResolution->getFeedbackProviderServiceClass(), $errorFeedbackItemResolution->getCode()]) {
            [
                CustomPostsMutationErrorFeedbackItemProvider::class,
                CustomPostsMutationErrorFeedbackItemProvider::E1,
            ] => new UserIsNotLoggedInErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                CustomPostsMutationErrorFeedbackItemProvider::class,
                CustomPostsMutationErrorFeedbackItemProvider::E2,
            ] => new LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                CustomPostsMutationErrorFeedbackItemProvider::class,
                CustomPostsMutationErrorFeedbackItemProvider::E3,
            ] => new LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                CustomPostsMutationErrorFeedbackItemProvider::class,
                CustomPostsMutationErrorFeedbackItemProvider::E8,
            ] => new LoggedInUserHasNoPermissionToEditCustomPostErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                CustomPostsMutationErrorFeedbackItemProvider::class,
                CustomPostsMutationErrorFeedbackItemProvider::E7,
            ] => new CustomPostDoesNotExistErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new MediaItemDoesNotExistErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
                $errorFeedbackItemResolution->getNamespacedCode(),
            ),
        };
    }
}
