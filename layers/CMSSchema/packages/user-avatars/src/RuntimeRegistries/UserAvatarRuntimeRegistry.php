<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatars\RuntimeRegistries;

use PoPCMSSchema\UserAvatars\ObjectModels\UserAvatar;

class UserAvatarRuntimeRegistry implements UserAvatarRuntimeRegistryInterface
{
    /** @var array<string|int,UserAvatar> */
    protected array $userAvatars = [];

    public function storeUserAvatar(UserAvatar $userAvatar): void
    {
        $this->userAvatars[$userAvatar->id] = $userAvatar;
    }

    public function getUserAvatar(string | int $id): ?UserAvatar
    {
        return $this->userAvatars[$id] ?? null;
    }
}
