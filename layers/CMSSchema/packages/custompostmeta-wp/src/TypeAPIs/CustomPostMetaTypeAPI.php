<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaWP\TypeAPIs;

use PoPCMSSchema\CustomPostMeta\TypeAPIs\AbstractCustomPostMetaTypeAPI;
use WP_Post;

class CustomPostMetaTypeAPI extends AbstractCustomPostMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetCustomPostMeta(string|int|object $customPostObjectOrID, string $key, bool $single = false): mixed
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
        }

        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existent and return null
        $value = \get_post_meta((int)$customPostID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
}
