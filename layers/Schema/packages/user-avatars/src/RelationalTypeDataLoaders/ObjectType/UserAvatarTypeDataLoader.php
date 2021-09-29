<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;

class UserAvatarTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry;

    #[Required]
    public function autowireUserAvatarTypeDataLoader(
        UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry,
    ): void {
        $this->userAvatarRuntimeRegistry = $userAvatarRuntimeRegistry;
    }

    public function getObjects(array $ids): array
    {
        return array_map(
            fn (string | int $id) => $this->userAvatarRuntimeRegistry->getUserAvatar($id),
            $ids
        );
    }
}
