<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutationsWP\TypeAPIs;

use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMediaTypeAPI implements CustomPostMediaTypeAPIInterface
{
    /**
     * @param mixed $customPostID
     * @param mixed $mediaItemID
     */
    public function setFeaturedImage($customPostID, $mediaItemID): void
    {
        \set_post_thumbnail($customPostID, $mediaItemID);
    }

    /**
     * @param mixed $customPostID
     */
    public function removeFeaturedImage($customPostID): void
    {
        \delete_post_thumbnail($customPostID);
    }
}
