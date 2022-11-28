<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoPCMSSchema\UserStateMutations\Exception\UserStateMutationException;
use PoPCMSSchema\UserStateMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserStateMutations\ObjectModels\InvalidUserEmailErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\PasswordIsIncorrectErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsLoggedInErrorPayload;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

class PayloadableLoginUserOneofMutationResolver extends LoginUserOneofMutationResolver
{
    use PayloadableMutationResolverTrait;

    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        /** @var ObjectDictionaryInterface */
        return $this->objectDictionary ??= $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
    }

    /**
     * Validate the app-level errors when executing the mutation,
     * return them in the Payload.
     *
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        parent::validateErrors($fieldDataAccessor, $separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createAndStoreFailureObjectMutationPayload(
                array_map(
                    $this->createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                )
            )->getID();
        }

        $userID = null;
        try {
            /** @var string|int */
            $userID = parent::executeMutation(
                $fieldDataAccessor,
                $separateObjectTypeFieldResolutionFeedbackStore,
            );
        } catch (UserStateMutationException $userStateMutationException) {
            if ($errorPayload = $this->identifyAndStoreSpecificErrorPayloadFromUserStateMutationException($userStateMutationException)) {
                return $this->createAndStoreFailureObjectMutationPayload(
                    [
                        $errorPayload,
                    ]
                )->getID();
            }
            return $this->createAndStoreFailureObjectMutationPayload(
                [
                    $this->createAndStoreGenericErrorPayloadFromPayloadClientException($userStateMutationException),
                ]
            )->getID();
        }

        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createAndStoreFailureObjectMutationPayload(
                array_map(
                    $this->createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                ),
                $userID
            )->getID();
        }

        /** @var string|int $userID */
        return $this->createAndStoreSuccessObjectMutationPayload($userID)->getID();
    }

    protected function identifyAndStoreSpecificErrorPayloadFromUserStateMutationException(
        UserStateMutationException $userStateMutationException
    ): ?ErrorPayloadInterface {
        $errorPayload = match ($userStateMutationException->getErrorCode()) {
            'incorrect_password' => new PasswordIsIncorrectErrorPayload(
                $userStateMutationException->getMessage(),
            ),
            'invalid_email' => new InvalidUserEmailErrorPayload(
                $userStateMutationException->getMessage(),
            ),
            default => null,
        };
        if ($errorPayload !== null) {
            $this->getObjectDictionary()->set(
                get_class($errorPayload),
                $errorPayload->getID(),
                $errorPayload,
            );
        }
        return $errorPayload;
    }

    /**
     * Override: Do nothing, because the app-level errors are
     * returned in the Payload, not in top-level "errors" entry.
     */
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
    }

    protected function createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorPayload = $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback);
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
        return match ([$errorFeedbackItemResolution->getFeedbackProviderServiceClass(), $errorFeedbackItemResolution->getCode()]) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E4,
            ] => new UserIsLoggedInErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $errorFeedbackItemResolution->getMessage(),
                $errorFeedbackItemResolution->getNamespacedCode(),
            ),
        };
    }
}
