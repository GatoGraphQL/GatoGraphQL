<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MediaTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Media
     */
    public function isInstanceOfMediaType(object $object): bool;

    public function getMediaItemSrc(string | int $media_id): ?string;
    public function getImageSrc(string | int $image_id, ?string $size = null): ?string;
    public function getImageSrcSet(string | int $image_id, ?string $size = null): ?string;
    public function getImageSizes(string | int $image_id, ?string $size = null): ?string;
    public function getImageProperties(string | int $image_id, ?string $size = null): ?array;
    public function getMediaItems(array $query, array $options = []): array;
    public function getMediaItemCount(array $query, array $options = []): int;
    public function getMediaItemID(object $media): string | int;
    public function getTitle(string | int | object $mediaObjectOrID): ?string;
    public function getCaption(string | int | object $mediaObjectOrID): ?string;
    public function getAltText(string | int | object $mediaObjectOrID): ?string;
    public function getDescription(string | int | object $mediaObjectOrID): ?string;
    public function getDate(string | int | object $mediaObjectOrID, bool $gmt = false): ?string;
    public function getModified(string | int | object $mediaObjectOrID, bool $gmt = false): ?string;
    public function getMimeType(string | int | object $mediaObjectOrID): ?string;
}
