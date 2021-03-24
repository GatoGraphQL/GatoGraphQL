<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutationsWP\TypeAPIs;

use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMediaTypeAPI implements CustomPostMediaTypeAPIInterface
{
    public function setFeaturedImage(int | string $customPostID, mixed $mediaItemID): void
    {
        \set_post_thumbnail($customPostID, $mediaItemID);
    }

    public function removeFeaturedImage(int | string $customPostID): void
    {
        \delete_post_thumbnail($customPostID);
    }
}
