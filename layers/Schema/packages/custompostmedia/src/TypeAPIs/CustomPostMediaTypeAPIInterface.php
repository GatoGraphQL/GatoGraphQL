<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\TypeAPIs;

interface CustomPostMediaTypeAPIInterface
{
    public function hasCustomPostThumbnail(string | int $post_id): bool;
    public function getCustomPostThumbnailID(string | int $post_id): string | int | null;
}
