<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MediaMutations\ObjectModels\MediaItemDoesNotExistErrorPayload;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait MediaItemCRUDMutationResolverTrait
{
    protected function validateMediaItemByIDExists(
        string|int $mediaItemID,
        string $fieldInputName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->getMediaTypeAPI()->mediaItemByIDExists($mediaItemID)) {
            $field = $fieldDataAccessor->getField();
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E6,
                        [
                            $mediaItemID,
                        ]
                    ),
                    $field->getArgument($fieldInputName) ?? $field,
                )
            );
        }
    }

    protected function validateMediaItemBySlugExists(
        string $mediaItemSlug,
        string $fieldInputName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->getMediaTypeAPI()->mediaItemBySlugExists($mediaItemSlug)) {
            $field = $fieldDataAccessor->getField();
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E7,
                        [
                            $mediaItemSlug,
                        ]
                    ),
                    $field->getArgument($fieldInputName) ?? $field,
                )
            );
        }
    }

    public function createMediaItemErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
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
                MutationErrorFeedbackItemProvider::E6,
            ] => new MediaItemDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new MediaItemDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => null,
        };
    }

    abstract protected function getMediaTypeAPI(): MediaTypeAPIInterface;

    protected function validateCanLoggedInUserEditMediaItem(
        string|int $mediaItemID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $userID = App::getState('current-user-id');
        if (!$this->getMediaTypeMutationAPI()->canUserEditMediaItem($userID, $mediaItemID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E8,
                        [
                            $mediaItemID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    abstract protected function getMediaTypeMutationAPI(): MediaTypeMutationAPIInterface;
}
