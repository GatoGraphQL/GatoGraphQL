<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\PayloadableMetaMutationResolverTrait;
use PoPCMSSchema\CommentMetaMutations\Constants\CommentMetaCRUDHookNames;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableCommentMutationResolverTrait as CommentMutationsPayloadableCommentMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCommentMetaMutationResolverTrait
{
    use CommentMutationsPayloadableCommentMutationResolverTrait {
        CommentMutationsPayloadableCommentMutationResolverTrait::createErrorPayloadFromObjectTypeFieldResolutionFeedback as upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback;
    }
    use PayloadableMetaMutationResolverTrait;

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return App::applyFilters(
            CommentMetaCRUDHookNames::ERROR_PAYLOAD,
            $this->createMetaMutationErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ) ?? $this->upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ),
            $objectTypeFieldResolutionFeedback,
        );
    }
}
