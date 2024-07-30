<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\Constants\HookNames;
use PoPCMSSchema\CategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CategoryMutations\ObjectModels\CategoryDoesNotExistErrorPayload;
use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoEditingCategoryCapabilityErrorPayload;
use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCategoryMutationResolverTrait
{
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
                $this->getUserNotLoggedInErrorFeedbackItemProviderClass(),
                $this->getUserNotLoggedInErrorFeedbackItemProviderCode(),
            ] => new UserIsNotLoggedInErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new LoggedInUserHasNoEditingCategoryCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E8,
            ] => new LoggedInUserHasNoPermissionToEditCustomPostErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new CategoryDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            // Allow components (eg: CustomPostCategoryMutations) to inject their own validations
            default => App::applyFilters(
                HookNames::ERROR_PAYLOAD,
                new GenericErrorPayload(
                    $feedbackItemResolution->getMessage(),
                    $feedbackItemResolution->getNamespacedCode(),
                ),
                $objectTypeFieldResolutionFeedback,
            )
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
