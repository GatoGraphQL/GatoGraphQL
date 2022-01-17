<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatars\RuntimeRegistries;

use PoPCMSSchema\UserAvatars\ObjectModels\UserAvatar;

interface UserAvatarRuntimeRegistryInterface
{
    public function storeUserAvatar(UserAvatar $userAvatar): void;
    public function getUserAvatar(string | int $id): ?UserAvatar;
}
