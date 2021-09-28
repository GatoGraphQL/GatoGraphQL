<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\ObjectModels;

class UserAvatar
{
    public function __construct(
        public string | int $id,
        public string $src,
        public int $size,
    ) {
    }
}
