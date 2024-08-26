<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\MediaMutations\Constants\MediaCRUDHookNames;
use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoPCMSSchema\Media\Constants\InputProperties;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use stdClass;

class CreateMediaItemMutationResolver extends AbstractCreateOrUpdateMediaItemMutationResolver
{
    protected function addMediaItemInputField(): bool
    {
        return false;
    }

    protected function canUploadAttachment(): bool
    {
        return true;
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
            MediaCRUDHookNames::VALIDATE_CREATE_MEDIA_ITEM,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function additionals(string|int $mediaItemID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($mediaItemID, $fieldDataAccessor);
        App::doAction(MediaCRUDHookNames::CREATE_MEDIA_ITEM, $mediaItemID, $fieldDataAccessor);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMediaItemData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return App::applyFilters(
            MediaCRUDHookNames::GET_CREATE_MEDIA_ITEM_DATA,
            parent::getMediaItemData($fieldDataAccessor),
            $fieldDataAccessor
        );
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    protected function createMediaItem(
        array $mediaItemData,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int|null {
        /** @var stdClass */
        $from = $fieldDataAccessor->getValue(MutationInputProperties::FROM);

        if (isset($from->{MutationInputProperties::URL})) {
            /** @var stdClass */
            $url = $from->{MutationInputProperties::URL};
            return $this->getMediaTypeMutationAPI()->createMediaItemFromURL(
                $url->{MutationInputProperties::SOURCE},
                $url->{MutationInputProperties::FILENAME},
                $mediaItemData,
            );
        }

        if (isset($from->{MutationInputProperties::MEDIAITEM_BY})) {
            /** @var string|int|null */
            $mediaItemID = null;
            /** @var stdClass */
            $mediaItemBy = $from->{MutationInputProperties::MEDIAITEM_BY};
            if (isset($mediaItemBy->{InputProperties::ID})) {
                $mediaItemID = $mediaItemBy->{InputProperties::ID};
            } elseif (isset($mediaItemBy->{InputProperties::SLUG})) {
                $mediaTypeAPI = $this->getMediaTypeAPI();
                /** @var string */
                $mediaItemSlug = $mediaItemBy->{InputProperties::SLUG};
                /** @var object */
                $mediaItem = $mediaTypeAPI->getMediaItemBySlug($mediaItemSlug);
                $mediaItemID = $mediaTypeAPI->getMediaItemID($mediaItem);
            }
            if ($mediaItemID === null) {
                return null;
            }
            return $this->getMediaTypeMutationAPI()->createMediaItemFromExistingMediaItem(
                $mediaItemID,
                $mediaItemData,
            );
        }

        /** @var stdClass */
        $contents = $from->{MutationInputProperties::CONTENTS};

        return $this->getMediaTypeMutationAPI()->createMediaItemFromContents(
            $contents->{MutationInputProperties::BODY},
            $contents->{MutationInputProperties::FILENAME},
            $mediaItemData,
        );
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $mediaItemData = $this->getMediaItemData($fieldDataAccessor);

        $mediaItemID = $this->createMediaItem(
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
