<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\CommentMetaMutations\Constants\CommentMetaCRUDHookNames;
use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableEditCommentToCustomPostMutationResolverTrait;
use PoPCMSSchema\MetaMutations\MutationResolvers\PayloadableMetaMutationResolverTrait;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCommentMetaMutationResolverTrait
{
    use PayloadableMetaMutationResolverTrait;
    use PayloadableEditCommentToCustomPostMutationResolverTrait;

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
            ) ?? $this->createEditCommentErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ) ?? App::applyFilters(
                CommentMetaCRUDHookNames::ERROR_PAYLOAD,
                new GenericErrorPayload(
                    $feedbackItemResolution->getMessage(),
                    $feedbackItemResolution->getNamespacedCode(),
                ),
                $objectTypeFieldResolutionFeedback,
            ),
        };
    }
}
