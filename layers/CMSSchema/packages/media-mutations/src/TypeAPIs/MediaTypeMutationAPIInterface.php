<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeAPIs;

use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;

interface MediaTypeMutationAPIInterface
{
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
        string $filename,
        string $body,
        array $mediaItemData,
    ): string|int;
}
