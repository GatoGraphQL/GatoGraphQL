<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostMediaTypeMutationAPIInterface
{
    public function setFeaturedImage(int | string $customPostID, string | int $mediaItemID): void;
    public function removeFeaturedImage(int | string $customPostID): void;
}
