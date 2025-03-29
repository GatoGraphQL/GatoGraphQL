<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MetaMutations\ObjectModels\AccessToMetaKeyIsNotAllowedErrorPayload;
use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaAlreadyHasSingleEntryErrorPayload;
use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaKeyDoesNotExistErrorPayload;
use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaKeyWithValueDoesNotExistErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableMetaMutationResolverTrait
{
    protected function createMetaMutationErrorPayloadFromObjectTypeFieldResolutionFeedback(
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
                MutationErrorFeedbackItemProvider::E1,
            ] => new TermMetaAlreadyHasSingleEntryErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new AccessToMetaKeyIsNotAllowedErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new AccessToMetaKeyIsNotAllowedErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E4,
            ] => new TermMetaKeyDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E5,
            ] => new TermMetaKeyWithValueDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => null,
        };
    }
}
