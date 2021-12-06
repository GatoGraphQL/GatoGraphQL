<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMetaWP\TypeAPIs;

use PoPSchema\CustomPostMeta\TypeAPIs\AbstractCustomPostMetaTypeAPI;

class CustomPostMetaTypeAPI extends AbstractCustomPostMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed
    {
        $value = \get_post_meta($customPostID, $key, $single);
        if ($value === '') {
            return null;
        }
        return $value;
    }
}
