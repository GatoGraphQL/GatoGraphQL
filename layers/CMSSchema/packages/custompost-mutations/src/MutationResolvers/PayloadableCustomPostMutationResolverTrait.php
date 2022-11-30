<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCustomPostMutationResolverTrait
{
    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorFeedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match ([$errorFeedbackItemResolution->getFeedbackProviderServiceClass(), $errorFeedbackItemResolution->getCode()]) {
            [
                $this->getUserNotLoggedInErrorFeedbackItemProviderClass(),
                $this->getUserNotLoggedInErrorFeedbackItemProviderCode(),
            ] => new UserIsNotLoggedInErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E8,
            ] => new LoggedInUserHasNoPermissionToEditCustomPostErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new CustomPostDoesNotExistErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
                $errorFeedbackItemResolution->getNamespacedCode(),
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
