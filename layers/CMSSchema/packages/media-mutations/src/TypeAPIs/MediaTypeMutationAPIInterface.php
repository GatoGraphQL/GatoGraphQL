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
    public function canUserEditMediaItems(
        string|int $userID,
    ): bool;
    public function canUserEditMediaItem(
        string|int $userID,
        string|int $mediaItemID,
    ): bool;
}
