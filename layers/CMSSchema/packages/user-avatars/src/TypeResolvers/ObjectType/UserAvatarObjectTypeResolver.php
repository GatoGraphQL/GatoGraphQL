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

    final public function setUserAvatarObjectTypeDataLoader(UserAvatarObjectTypeDataLoader $userAvatarObjectTypeDataLoader): void
    {
        $this->userAvatarObjectTypeDataLoader = $userAvatarObjectTypeDataLoader;
    }
    final protected function getUserAvatarObjectTypeDataLoader(): UserAvatarObjectTypeDataLoader
    {
        /** @var UserAvatarObjectTypeDataLoader */
        return $this->userAvatarObjectTypeDataLoader ??= $this->instanceManager->getInstance(UserAvatarObjectTypeDataLoader::class);
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
