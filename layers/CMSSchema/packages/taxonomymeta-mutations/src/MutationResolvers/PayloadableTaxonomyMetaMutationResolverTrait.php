<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\PayloadableMetaMutationResolverTrait;
use PoPCMSSchema\TaxonomyMetaMutations\Constants\TaxonomyMetaCRUDHookNames;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\PayloadableTaxonomyMutationResolverTrait as TaxonomyMutationsPayloadableTaxonomyMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableTaxonomyMetaMutationResolverTrait
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
            TaxonomyMetaCRUDHookNames::ERROR_PAYLOAD,
            $this->createMetaMutationErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ) ?? $this->upstreamCreateErrorPayloadFromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedback,
            ),
            $objectTypeFieldResolutionFeedback,
        );
    }
}
