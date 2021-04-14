<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMetaWP\TypeAPIs;

use PoPSchema\CustomPostMeta\TypeAPIs\AbstractCustomPostMetaTypeAPI;

class CustomPostMetaTypeAPI extends AbstractCustomPostMetaTypeAPI
{
    protected function doGetCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed
    {
        return \get_post_meta($customPostID, $key, $single);
    }
}
