<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;

class UserAvatarTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry = null;

    final public function setUserAvatarRuntimeRegistry(UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry): void
    {
        $this->userAvatarRuntimeRegistry = $userAvatarRuntimeRegistry;
    }
    final protected function getUserAvatarRuntimeRegistry(): UserAvatarRuntimeRegistryInterface
    {
        return $this->userAvatarRuntimeRegistry ??= $this->instanceManager->getInstance(UserAvatarRuntimeRegistryInterface::class);
    }

    public function getObjects(array $ids): array
    {
        return array_map(
            fn (string | int $id) => $this->getUserAvatarRuntimeRegistry()->getUserAvatar($id),
            $ids
        );
    }
}
