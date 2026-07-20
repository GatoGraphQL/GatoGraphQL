<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\MenuMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;
use PoPCMSSchema\MenuMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MenuMutations\ObjectModels\LoggedInUserHasNoPermissionToDeleteMenuErrorPayload;
use PoPCMSSchema\MenuMutations\ObjectModels\MenuDoesNotExistErrorPayload;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\MutationResolvers\PayloadableMutationResolverTrait;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;

trait PayloadableDeleteMenuMutationResolverTrait
{
    use PayloadableMutationResolverTrait, DeleteMenuMutationResolverTrait {
        DeleteMenuMutationResolverTrait::executeMutation as upstreamExecuteMutation;
        PayloadableMutationResolverTrait::validate insteadof DeleteMenuMutationResolverTrait;
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
        $this->validateDeleteErrors($fieldDataAccessor, $separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return $this->createFailureObjectMutationPayload(
                array_map(
                    $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
                    $separateObjectTypeFieldResolutionFeedbackStore->getErrors()
                )
            )->getID();
        }

        /** @var string|int */
        $menuID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        /**
         * Deleting the menu either succeeds, or throws an exception.
         * Hence there are no errors to collect after executing the mutation.
         */
        try {
            $this->upstreamExecuteMutation(
                $fieldDataAccessor,
                $separateObjectTypeFieldResolutionFeedbackStore,
            );
        } catch (MenuCRUDMutationException $menuCRUDMutationException) {
            return $this->createFailureObjectMutationPayload(
                [
                    $this->createGenericErrorPayloadFromPayloadClientException($menuCRUDMutationException),
                ]
            )->getID();
        }

        return $this->createSuccessObjectMutationPayload($menuID)->getID();
    }

    protected function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
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
                MutationErrorFeedbackItemProvider::E6,
            ] => new MenuDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E10,
            ] => new UserIsNotLoggedInErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E11,
            ] => new LoggedInUserHasNoPermissionToDeleteMenuErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => new GenericErrorPayload(
                $feedbackItemResolution->getMessage(),
                $feedbackItemResolution->getNamespacedCode(),
            ),
        };
    }
}
