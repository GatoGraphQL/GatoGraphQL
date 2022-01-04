<?php

declare(strict_types=1);

namespace PoPSchema\MediaWP\TypeAPIs;

use PoP\Root\Managers\ComponentManager;
use PoPSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPSchema\Media\Component;
use PoPSchema\Media\ComponentConfiguration;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
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
        return ($object instanceof WP_Post) && $object->post_type == 'attachment';
    }

    public function getMediaItemSrc(string | int $media_id): ?string
    {
        return \wp_get_attachment_url($media_id);
    }

    public function getImageSrc(string | int $image_id, ?string $size = null): ?string
    {
        $img = $this->getImageProperties($image_id, $size);
        if ($img === null) {
            return null;
        }
        return $img['src'];
    }

    public function getImageSrcSet(string | int $image_id, ?string $size = null): ?string
    {
        $srcSet = \wp_get_attachment_image_srcset($image_id, $size);
        if ($srcSet === false) {
            return null;
        }
        return $srcSet;
    }

    public function getImageSizes(string | int $image_id, ?string $size = null): ?string
    {
        $imageProperties = $this->getImageProperties($image_id, $size);
        if ($imageProperties === null) {
            return null;
        }
        $imageSize = [$imageProperties['width'], $imageProperties['height']];
        $sizes = \wp_calculate_image_sizes($imageSize, $imageProperties['src'], null, $image_id);
        if ($sizes === false) {
            return null;
        }
        return $sizes;
    }

    public function getImageProperties(string | int $image_id, ?string $size = null): ?array
    {
        $img = wp_get_attachment_image_src($image_id, $size);
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
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        $query = parent::convertCustomPostsQuery($query, $options);

        $query = $this->convertMediaQuery($query, $options);

        return $this->getHooksAPI()->applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @return array<string, mixed>
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
     * @return array<string, mixed>
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
     * Limit of how many custom posts can be retrieved in the query.
     * Override this value for specific custom post types
     */
    protected function getCustomPostListMaxLimit(): int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getMediaListMaxLimit();
    }

    public function getMediaItems(array $query, array $options = []): array
    {
        return $this->getCustomPosts($query, $options);
    }
    public function getMediaItemCount(array $query = [], array $options = []): int
    {
        return $this->getCustomPostCount($query, $options);
    }

    protected function convertMediaQuery($query, array $options = [])
    {
        if (isset($query['mime-types'])) {
            // Transform from array to string
            $query['post_mime_type'] = implode(',', $query['mime-types']);
            unset($query['mime-types']);
        }

        return $query;
    }

    public function getMediaItemID(object $media): string | int
    {
        return $media->ID;
    }

    public function getTitle(string | int | object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_title;
    }

    public function getCaption(string | int | object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_excerpt;
    }

    public function getAltText(string | int | object $mediaObjectOrID): ?string
    {
        $mediaItemID = $this->getCustomPostID($mediaObjectOrID);
        if ($mediaItemID === null) {
            return null;
        }
        return get_post_meta($mediaItemID, '_wp_attachment_image_alt', true);
    }

    public function getDescription(string | int | object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_content;
    }

    public function getDate(string | int | object $mediaObjectOrID, bool $gmt = false): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $gmt ? $mediaItem->post_date_gmt : $mediaItem->post_date;
    }

    public function getModified(string | int | object $mediaObjectOrID, bool $gmt = false): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $gmt ? $mediaItem->post_modified_gmt : $mediaItem->post_modified;
    }

    public function getMimeType(string | int | object $mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_mime_type;
    }
}
