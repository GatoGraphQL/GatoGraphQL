<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MediaTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Media
     */
    public function isInstanceOfMediaType(object $object): bool;

    public function getMediaItemSrc(string|int|object $mediaItemObjectOrID): ?string;
    public function getImageSrc(string|int|object $mediaItemObjectOrID, ?string $size = null): ?string;
    public function getImageSrcSet(string|int|object $mediaItemObjectOrID, ?string $size = null): ?string;
    public function getImageSizes(string|int|object $mediaItemObjectOrID, ?string $size = null): ?string;
    /**
     * @return array{src:string,width:?int,height:?int}
     */
    public function getImageProperties(string|int|object $mediaItemObjectOrID, ?string $size = null): ?array;
    /**
     * Get the media item with provided ID or, if it doesn't exist, null
     */
    public function getMediaItem(int|string $id): ?object;
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItems(array $query, array $options = []): array;
    public function mediaItemExists(int|string $id): bool;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItemCount(array $query, array $options = []): int;
    public function getMediaItemID(object $media): string|int;
    public function getTitle(string|int|object $mediaObjectOrID): ?string;
    public function getCaption(string|int|object $mediaObjectOrID): ?string;
    public function getAltText(string|int|object $mediaObjectOrID): ?string;
    public function getDescription(string|int|object $mediaObjectOrID): ?string;
    public function getDate(string|int|object $mediaObjectOrID, bool $gmt = false): ?string;
    public function getModified(string|int|object $mediaObjectOrID, bool $gmt = false): ?string;
    public function getMimeType(string|int|object $mediaObjectOrID): ?string;
}
