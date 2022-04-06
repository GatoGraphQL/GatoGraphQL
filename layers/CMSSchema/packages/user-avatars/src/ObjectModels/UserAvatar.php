<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatars\ObjectModels;

class UserAvatar
{
    public function __construct(
        public readonly string | int $id,
        public readonly string $src,
        public readonly int $size,
    ) {
    }
}
