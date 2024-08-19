<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\Constants\HookNames;
use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;

class UpdateMediaItemMutationResolver extends AbstractCreateOrUpdateMediaItemMutationResolver
{
    protected function addMediaItemInputField(): bool
    {
        return true;
    }

    protected function canUploadAttachment(): bool
    {
        return false;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validate(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        // Allow components to inject their own validations
        App::doAction(
            HookNames::VALIDATE_UPDATE_MEDIA_ITEM,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function additionals(string|int $mediaItemID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($mediaItemID, $fieldDataAccessor);
        App::doAction(HookNames::UPDATE_MEDIA_ITEM, $mediaItemID, $fieldDataAccessor);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMediaItemData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return App::applyFilters(
            HookNames::GET_UPDATE_MEDIA_ITEM_DATA,
            parent::getMediaItemData($fieldDataAccessor),
            $fieldDataAccessor
        );
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    protected function updateMediaItem(
        array $mediaItemData,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int|null {
        /** @var string|int */
        $mediaItemID = $mediaItemData['id'];
        unset($mediaItemData['id']);

        $this->getMediaTypeMutationAPI()->updateMediaItem(
            $mediaItemID,
            $mediaItemData,
        );

        return $mediaItemID;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $mediaItemData = $this->getMediaItemData($fieldDataAccessor);

        $mediaItemID = $this->updateMediaItem(
            $mediaItemData,
            $fieldDataAccessor,
        );

        if ($mediaItemID === null) {
            return null;
        }

        // Allow for additional operations
        $this->additionals($mediaItemID, $fieldDataAccessor);

        return $mediaItemID;
    }
}
