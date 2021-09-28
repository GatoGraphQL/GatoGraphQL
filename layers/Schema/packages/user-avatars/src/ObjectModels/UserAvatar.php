<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\ObjectModels;

class UserAvatar
{
    public string | int $id;
    public string $src;
    public int $size;
    public function __construct(string | int $id, string $src, int $size)
    {
        $this->id = $id;
        $this->src = $src;
        $this->size = $size;
    }
}
