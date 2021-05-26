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

    public function getImageSrc(string | int $image_id, ?string $size = null): ?string;
    public function getMediaAuthorId(string | int $media_id): string | int | null;
    public function getMediaElements(array $query, array $options = []): array;
    public function getMediaElementId(object $media): string | int;
}
