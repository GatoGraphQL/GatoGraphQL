<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MetaMutations\ObjectModels\MetaKeyNotAllowedErrorPayload;
use PoPCMSSchema\MetaMutations\ObjectModels\TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableMetaMutationResolverTrait
{
    protected function createMetaMutationErrorPayloadFromObjectTypeFieldResolutionFeedback(
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
            ] => new TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new MetaKeyNotAllowedErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new MetaKeyNotAllowedErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => null,
        };
    }
}
