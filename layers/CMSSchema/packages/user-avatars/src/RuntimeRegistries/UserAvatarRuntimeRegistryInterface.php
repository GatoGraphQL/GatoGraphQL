<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\RuntimeRegistries;

use PoPSchema\UserAvatars\ObjectModels\UserAvatar;

interface UserAvatarRuntimeRegistryInterface
{
    public function storeUserAvatar(UserAvatar $userAvatar): void;
    public function getUserAvatar(string | int $id): ?UserAvatar;
}
