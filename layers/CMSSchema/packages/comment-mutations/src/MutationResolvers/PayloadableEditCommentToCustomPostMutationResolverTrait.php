<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CommentMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCommentErrorPayload;
use PoPCMSSchema\CommentMutations\ObjectModels\CommentDoesNotExistErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableEditCommentToCustomPostMutationResolverTrait
{
    protected function createEditCommentErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
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
                MutationErrorFeedbackItemProvider::E10,
            ] => new CommentDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E11,
            ] => new LoggedInUserHasNoPermissionToEditCommentErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => null,
        };
    }
}
