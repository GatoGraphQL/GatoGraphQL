<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaWP\TypeAPIs;

use PoPCMSSchema\CustomPostMeta\TypeAPIs\AbstractCustomPostMetaTypeAPI;
use WP_Post;

use function get_post_meta;

class CustomPostMetaTypeAPI extends AbstractCustomPostMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetCustomPostMeta(string|int|object $customPostObjectOrID, string $key, bool $single = false): mixed
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);

        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existent and return null
        $value = \get_post_meta($customPostID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
    
    /**
     * Extract the custom post ID from either a WP_Post object or ID
     */
    protected function getCustomPostID(string|int|object $customPostObjectOrID): int
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            return $customPost->ID;
        }
        return (int) $customPostObjectOrID;
    }

    /**
     * @return array<string,mixed>
     */
    public function getAllCustomPostMeta(string|int|object $customPostObjectOrID): array
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);

        $meta = get_post_meta($customPostID) ?? [];
        if (!is_array($meta)) {
            return [];
        }

        return array_map(
            /**
             * @param mixed[] $items
             * @return mixed[]
             */
            function (array $items): array {
                return array_map(
                    \maybe_unserialize(...),
                    $items
                );
            },
            $meta
        );
    }

    /**
     * @return string[]
     */
    public function getCustomPostMetaKeys(string|int|object $customPostObjectOrID): array
    {
        return array_keys($this->getAllCustomPostMeta($customPostObjectOrID));
    }
}
