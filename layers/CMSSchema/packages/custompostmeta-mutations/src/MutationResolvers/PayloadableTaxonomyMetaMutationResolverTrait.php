<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\PayloadableMetaMutationResolverTrait;
use PoPCMSSchema\CustomPostMetaMutations\Constants\CustomPostMetaCRUDHookNames;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\PayloadableTaxonomyMutationResolverTrait as TaxonomyMutationsPayloadableTaxonomyMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCustomPostMetaMutationResolverTrait
{
    use TaxonomyMutationsPayloadableTaxonomyMutationResolverTrait {
        TaxonomyMutationsPayloadableTaxonomyMutationResolverTrait::createErrorPayloadFromObjectTypeFieldResolutionFeedback as upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback;
    }
    use PayloadableMetaMutationResolverTrait;

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
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
