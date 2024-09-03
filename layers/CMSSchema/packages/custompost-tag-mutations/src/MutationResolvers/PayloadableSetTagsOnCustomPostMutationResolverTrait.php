<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableSetTagsOnCustomPostMutationResolverTrait
{
    use PayloadableCustomPostMutationResolverTrait {
        PayloadableCustomPostMutationResolverTrait::createErrorPayloadFromObjectTypeFieldResolutionFeedback as upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback;
    }
    use SetTagsOnCustomPostMutationResolverTrait;

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        return $this->createSetTagsOnCustomPostErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback)
            ?? $this->upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback);
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
