<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostCategoryMetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableCustomPostMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableSetMetaOnCategoryMutationResolverTrait
{
    use PayloadableCustomPostMutationResolverTrait {
        PayloadableCustomPostMutationResolverTrait::createErrorPayloadFromObjectTypeFieldResolutionFeedback as upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback;
    }
    use SetMetaOnCategoryMutationResolverTrait;

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        return $this->createSetMetaOnCategoryErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback)
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
