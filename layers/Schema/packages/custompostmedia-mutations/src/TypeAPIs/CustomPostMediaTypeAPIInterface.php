<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostMediaTypeAPIInterface
{
    /**
     * @param mixed $customPostID
     * @param mixed $mediaItemID
     */
    public function setFeaturedImage($customPostID, $mediaItemID): void;

    /**
     * @param mixed $customPostID
     */
    public function removeFeaturedImage($customPostID): void;
}
