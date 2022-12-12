<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMedia\TypeAPIs;

interface CustomPostMediaTypeAPIInterface
{
    public function doesCustomPostTypeSupportFeaturedImage(string $customPostType): bool;
    public function hasCustomPostThumbnail(string|int|object $customPostObjectOrID): bool;
    public function getCustomPostThumbnailID(string|int|object $customPostObjectOrID): string|int|null;
}
