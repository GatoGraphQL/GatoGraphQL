<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPCMSSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType\UserAvatarObjectTypeDataLoader;

class UserAvatarObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?UserAvatarObjectTypeDataLoader $userAvatarObjectTypeDataLoader = null;

    final protected function getUserAvatarObjectTypeDataLoader(): UserAvatarObjectTypeDataLoader
    {
        if ($this->userAvatarObjectTypeDataLoader === null) {
            /** @var UserAvatarObjectTypeDataLoader */
            $userAvatarObjectTypeDataLoader = $this->instanceManager->getInstance(UserAvatarObjectTypeDataLoader::class);
            $this->userAvatarObjectTypeDataLoader = $userAvatarObjectTypeDataLoader;
        }
        return $this->userAvatarObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserAvatar';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('User avatar', 'user-avatars');
    }

    public function getID(object $object): string|int|null
    {
        /** @var UserAvatar */
        $userAvatar = $object;
        return $userAvatar->id;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserAvatarObjectTypeDataLoader();
    }
}
