<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\UserMetaMutations\Constants\UserMetaCRUDHookNames;
use PoPCMSSchema\UserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserMutations\MutationResolvers\PayloadableEditUserMutationResolverTrait;
use PoPCMSSchema\MetaMutations\MutationResolvers\PayloadableMetaMutationResolverTrait;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableUserMetaMutationResolverTrait
{
    use PayloadableMetaMutationResolverTrait;
    use PayloadableEditUserMutationResolverTrait;

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
                MutationErrorFeedbackItemProvider::E1,
            ] => new UserIsNotLoggedInErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => $this->createMetaMutationErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ) ?? $this->createEditUserErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ) ?? App::applyFilters(
                UserMetaCRUDHookNames::ERROR_PAYLOAD,
                new GenericErrorPayload(
                    $feedbackItemResolution->getMessage(),
                    $feedbackItemResolution->getNamespacedCode(),
                ),
                $objectTypeFieldResolutionFeedback,
            ),
        };
    }
}
