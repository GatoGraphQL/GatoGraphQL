<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPCMSSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;

class UserAvatarObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry = null;

    final protected function getUserAvatarRuntimeRegistry(): UserAvatarRuntimeRegistryInterface
    {
        if ($this->userAvatarRuntimeRegistry === null) {
            /** @var UserAvatarRuntimeRegistryInterface */
            $userAvatarRuntimeRegistry = $this->instanceManager->getInstance(UserAvatarRuntimeRegistryInterface::class);
            $this->userAvatarRuntimeRegistry = $userAvatarRuntimeRegistry;
        }
        return $this->userAvatarRuntimeRegistry;
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        return array_map(
            $this->getUserAvatarRuntimeRegistry()->getUserAvatar(...),
            $ids
        );
    }
}
