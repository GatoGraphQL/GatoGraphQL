<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\Exception\AbstractPayloadClientException;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCreateOrUpdateCustomPostMutationResolverTrait
{
    abstract protected function getObjectDictionary(): ObjectDictionaryInterface;
    
    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $objectTypeFieldResolutionFeedbacks
     * @return ErrorPayloadInterface[]
     */
    protected function createAndStoreErrorPayloadsFromObjectTypeFieldResolutionFeedbacks(
        array $objectTypeFieldResolutionFeedbacks
    ): array {
        $errorPayloads = [];
        foreach ($objectTypeFieldResolutionFeedbacks as $objectTypeFieldResolutionFeedback) {
            $errorPayload = $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback);
            $this->getObjectDictionary()->set(
                get_class($errorPayload),
                $errorPayload->getID(),
                $errorPayload,
            );
            $errorPayloads[] = $errorPayload;
        }
        return $errorPayloads;
    }
    
    protected function createAndStoreGenericErrorPayloadFromPayloadClientException(
        AbstractPayloadClientException $payloadClientException
    ): GenericErrorPayload {
            $errorPayload = new GenericErrorPayload(
                $payloadClientException->getMessage(),
                $payloadClientException->getErrorCode(),
                $payloadClientException->getData(),
            );
            $this->getObjectDictionary()->set(
                get_class($errorPayload),
                $errorPayload->getID(),
                $errorPayload,
            );
            return $errorPayload;
    }

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorFeedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        /** @var ErrorPayloadInterface */
        return match ([$errorFeedbackItemResolution->getFeedbackProviderServiceClass(), $errorFeedbackItemResolution->getCode()]) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
            ] => new UserIsNotLoggedInErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new CustomPostDoesNotExistErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E8,
            ] => new LoggedInUserHasNoPermissionToEditCustomPostErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
                $errorFeedbackItemResolution->getCode(),
            ),
        };
    }
}
