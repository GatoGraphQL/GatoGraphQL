<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutationsWP\TypeAPIs;

use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMediaTypeMutationAPI implements CustomPostMediaTypeMutationAPIInterface
{
    public function setFeaturedImage(int | string $customPostID, string | int $mediaItemID): void
    {
        \set_post_thumbnail($customPostID, $mediaItemID);
    }

    public function removeFeaturedImage(int | string $customPostID): void
    {
        \delete_post_thumbnail($customPostID);
    }
}
