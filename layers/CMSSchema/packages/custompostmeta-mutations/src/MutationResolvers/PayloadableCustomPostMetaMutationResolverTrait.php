<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\PayloadableMetaMutationResolverTrait;
use PoPCMSSchema\CustomPostMetaMutations\Constants\CustomPostMetaCRUDHookNames;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableCustomPostMutationResolverTrait as CustomPostMutationsPayloadableCustomPostMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCustomPostMetaMutationResolverTrait
{
    use CustomPostMutationsPayloadableCustomPostMutationResolverTrait {
        CustomPostMutationsPayloadableCustomPostMutationResolverTrait::createErrorPayloadFromObjectTypeFieldResolutionFeedback as upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback;
    }
    use PayloadableMetaMutationResolverTrait;

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        return App::applyFilters(
            CustomPostMetaCRUDHookNames::ERROR_PAYLOAD,
            $this->createMetaMutationErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ) ?? $this->upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ),
            $objectTypeFieldResolutionFeedback,
        );
    }
}
