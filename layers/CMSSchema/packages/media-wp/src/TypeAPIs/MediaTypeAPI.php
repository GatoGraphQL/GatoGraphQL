<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use WP_Post;

use function wp_get_attachment_image_src;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class MediaTypeAPI extends AbstractCustomPostTypeAPI implements MediaTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Indicates if the passed object is of type Media
     */
    public function isInstanceOfMediaType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type === 'attachment';
    }

    public function getMediaItemSrc(string|int $media_id): ?string
    {
        $url = \wp_get_attachment_url((int)$media_id);
        if ($url === false) {
            return null;
        }
        return $url;
    }

    public function getImageSrc(string|int $image_id, ?string $size = null): ?string
    {
        $img = $this->getImageProperties($image_id, $size);
        if ($img === null) {
            return null;
        }
        return $img['src'];
    }

    public function getImageSrcSet(string|int|object $mediaItemObjectOrID, ?string $size = null): ?string
    {
        if (is_object($mediaItemObjectOrID)) {
            /** @var WP_Post */
            $mediaItemObject = $mediaItemObjectOrID;
            $mediaItemID = $mediaItemObject->ID;
        } else {
            $mediaItemID = $mediaItemObjectOrID;
        }
        $srcSet = \wp_get_attachment_image_srcset((int)$mediaItemID, $size);
        if ($srcSet === false) {
            return null;
        }
        return $srcSet;
    }

    public function getImageSizes(string|int|object $mediaItemObjectOrID, ?string $size = null): ?string
    {
        $imageProperties = $this->getImageProperties($mediaItemObjectOrID, $size);
        if ($imageProperties === null) {
            return null;
        }
        if (is_object($mediaItemObjectOrID)) {
            /** @var WP_Post */
            $mediaItemObject = $mediaItemObjectOrID;
            $mediaItemID = $mediaItemObject->ID;
        } else {
            $mediaItemID = $mediaItemObjectOrID;
        }
        /** @var int[] */
        $imageSize = [(int)$imageProperties['width'], (int)$imageProperties['height']];
        $sizes = \wp_calculate_image_sizes($imageSize, $imageProperties['src'], null, (int)$mediaItemID);
        if ($sizes === false) {
            return null;
        }
        return $sizes;
    }

    /**
     * @return array{src: string, width: ?int, height: ?int}
     */
    public function getImageProperties(string|int|object $mediaItemObjectOrID, ?string $size = null): ?array
    {
        if (is_object($mediaItemObjectOrID)) {
            /** @var WP_Post */
            $mediaItemObject = $mediaItemObjectOrID;
            $mediaItemID = $mediaItemObject->ID;
        } else {
            $mediaItemID = $mediaItemObjectOrID;
        }
        $img = wp_get_attachment_image_src((int)$mediaItemID, $size);
        if ($img === false) {
            return null;
        }
        return [
            'src' => $img[0],
            'width' => $img[1],
            'height' => $img[2]
        ];
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        $query = parent::convertCustomPostsQuery($query, $options);

        $query = $this->convertMediaQuery($query, $options);

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function getCustomPostQueryDefaults(): array
    {
        // For media, must remove the status or the query doesn't work
        $queryDefaults = parent::getCustomPostQueryDefaults();
        unset($queryDefaults['status']);
        return $queryDefaults;
    }

    /**
     * Query args that must always be in the query
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return array_merge(
            parent::getCustomPostQueryRequiredArgs(),
            [
                'custompost-types' => ['attachment'],
            ]
        );
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItems(array $query, array $options = []): array
    {
        return $this->getCustomPosts($query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItemCount(array $query = [], array $options = []): int
    {
        return $this->getCustomPostCount($query, $options);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertMediaQuery(array $query, array $options = []): array
    {
        if (isset($query['mime-types'])) {
            // Transform from array to string
            $query['post_mime_type'] = implode(',', $query['mime-types']);
            unset($query['mime-types']);
        }

        return $query;
    }

    public function getMediaItemID(object $mediaItem): string|int
    {
        /** @var WP_Post $mediaItem */
        return $mediaItem->ID;
    }

    public function getTitle(string|int|object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_title;
    }

    public function getCaption(string|int|object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_excerpt;
    }

    public function getAltText(string|int|object $mediaObjectOrID): ?string
    {
        $mediaItemID = $this->getCustomPostID($mediaObjectOrID);
        return get_post_meta($mediaItemID, '_wp_attachment_image_alt', true);
    }

    public function getDescription(string|int|object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_content;
    }

    public function getDate(string|int|object $mediaObjectOrID, bool $gmt = false): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $gmt ? $mediaItem->post_date_gmt : $mediaItem->post_date;
    }

    public function getModified(string|int|object $mediaObjectOrID, bool $gmt = false): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $gmt ? $mediaItem->post_modified_gmt : $mediaItem->post_modified;
    }

    public function getMimeType(string|int|object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_mime_type;
    }
}
