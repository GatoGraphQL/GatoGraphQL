<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType\UserAvatarTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class UserAvatarObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?UserAvatarTypeDataLoader $userAvatarTypeDataLoader = null;

    public function setUserAvatarTypeDataLoader(UserAvatarTypeDataLoader $userAvatarTypeDataLoader): void
    {
        $this->userAvatarTypeDataLoader = $userAvatarTypeDataLoader;
    }
    protected function getUserAvatarTypeDataLoader(): UserAvatarTypeDataLoader
    {
        return $this->userAvatarTypeDataLoader ??= $this->instanceManager->getInstance(UserAvatarTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'UserAvatar';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('User avatar', 'user-avatars');
    }

    public function getID(object $object): string | int | null
    {
        /** @var UserAvatar */
        $userAvatar = $object;
        return $userAvatar->id;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserAvatarTypeDataLoader();
    }
}
