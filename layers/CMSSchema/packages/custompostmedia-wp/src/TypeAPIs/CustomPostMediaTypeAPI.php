<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaWP\TypeAPIs;

use PoPSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;

use function get_post_thumbnail_id;
use function has_post_thumbnail;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMediaTypeAPI implements CustomPostMediaTypeAPIInterface
{
    public function hasCustomPostThumbnail(string | int $post_id): bool
    {
        return has_post_thumbnail($post_id);
    }

    public function getCustomPostThumbnailID(string | int $post_id): string | int | null
    {
        if ($id = get_post_thumbnail_id($post_id)) {
            return (int)$id;
        }
        return null;
    }
}
