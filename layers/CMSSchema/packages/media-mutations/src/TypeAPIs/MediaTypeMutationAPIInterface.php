<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeAPIs;

use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;

interface MediaTypeMutationAPIInterface
{
    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromExistingMediaItem(
        string|int $existingMediaItemID,
        array $mediaItemData,
    ): string|int|null;
    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param string|null $filename Override the filename from the URL, or pass `null` to use filename from URL
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromURL(
        string $url,
        ?string $filename,
        array $mediaItemData,
    ): string|int;
    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromContents(
        string $body,
        string $filename,
        array $mediaItemData,
    ): string|int;
    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function updateMediaItem(
        string|int $mediaItemID,
        array $mediaItemData,
    ): void;
    /**
     * @throws MediaItemCRUDMutationException In case of error
     */
    public function trashMediaItem(
        string|int $mediaItemID,
    ): void;
    /**
     * @throws MediaItemCRUDMutationException In case of error
     */
    public function deleteMediaItem(
        string|int $mediaItemID,
    ): void;
    /**
     * Whether media items can be sent to the trash, or must
     * always be deleted permanently.
     */
    public function doesMediaItemSupportTrash(): bool;
    public function isMediaItemInTrash(
        string|int $mediaItemID,
    ): bool;
    public function canUserEditMediaItems(
        string|int $userID,
    ): bool;
    public function canUserEditMediaItem(
        string|int $userID,
        string|int $mediaItemID,
    ): bool;
    public function canUserDeleteMediaItem(
        string|int $userID,
        string|int $mediaItemID,
    ): bool;
}
