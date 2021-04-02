<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMetaWP\TypeAPIs;

use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMetaTypeAPI implements CustomPostMetaTypeAPIInterface
{
    public function getCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed
    {
        return \get_post_meta($customPostID, $key, $single);
    }
}
