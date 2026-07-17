<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoPCMSSchema\MediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractDeleteMediaItemMutationResolver extends AbstractMutationResolver
{
    use DeleteMediaItemMutationResolverTrait;
    use ValidateUserLoggedInMutationResolverTrait;

    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?MediaTypeMutationAPIInterface $mediaTypeMutationAPI = null;

    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        if ($this->mediaTypeAPI === null) {
            /** @var MediaTypeAPIInterface */
            $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
            $this->mediaTypeAPI = $mediaTypeAPI;
        }
        return $this->mediaTypeAPI;
    }

    final protected function getMediaTypeMutationAPI(): MediaTypeMutationAPIInterface
    {
        if ($this->mediaTypeMutationAPI === null) {
            /** @var MediaTypeMutationAPIInterface */
            $mediaTypeMutationAPI = $this->instanceManager->getInstance(MediaTypeMutationAPIInterface::class);
            $this->mediaTypeMutationAPI = $mediaTypeMutationAPI;
        }
        return $this->mediaTypeMutationAPI;
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E10,
        );
    }

    protected function validateDeleteErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        /** @var string|int|null */
        $mediaItemID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateMediaItemExists($mediaItemID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int */
        $mediaItemID = $mediaItemID;

        $this->validateCanLoggedInUserDeleteMediaItem($mediaItemID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanMediaItemBeTrashed($mediaItemID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Validate that the media item exists. The ID is mandatory in the
     * input, so a missing ID should never happen.
     */
    protected function validateMediaItemExists(
        string|int|null $mediaItemID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$mediaItemID || !$this->getMediaTypeAPI()->mediaItemByIDExists($mediaItemID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E6,
                        [
                            $mediaItemID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    /**
     * Check that the logged-in user can delete this specific media item.
     *
     * The `delete_post` meta capability is resolved by the CMS, which takes
     * into account the ownership of the media item and its status, hence
     * this single check also covers "delete others'" and "delete published".
     */
    protected function validateCanLoggedInUserDeleteMediaItem(
        string|int $mediaItemID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $userID = App::getState('current-user-id');
        if ($this->getMediaTypeMutationAPI()->canUserDeleteMediaItem($userID, $mediaItemID)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E11,
                    [
                        $mediaItemID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    /**
     * When not deleting permanently, the media item must support being
     * sent to the trash, and must not be in the trash already.
     */
    protected function validateCanMediaItemBeTrashed(
        string|int $mediaItemID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->isForceDelete($fieldDataAccessor)) {
            return;
        }

        if (!$this->getMediaTypeMutationAPI()->doesMediaItemSupportTrash()) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E12,
                        [
                            $mediaItemID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        if ($this->getMediaTypeMutationAPI()->isMediaItemInTrash($mediaItemID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E13,
                        [
                            $mediaItemID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function isForceDelete(FieldDataAccessorInterface $fieldDataAccessor): bool
    {
        return $fieldDataAccessor->getValue(MutationInputProperties::FORCE) === true;
    }

    /**
     * @return bool Whether the media item was deleted
     * @throws MediaItemCRUDMutationException If there was an error (eg: Media Item does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool {
        /** @var string|int */
        $mediaItemID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        if ($this->isForceDelete($fieldDataAccessor)) {
            $this->getMediaTypeMutationAPI()->deleteMediaItem($mediaItemID);
        } else {
            $this->getMediaTypeMutationAPI()->trashMediaItem($mediaItemID);
        }

        return true;
    }
}
