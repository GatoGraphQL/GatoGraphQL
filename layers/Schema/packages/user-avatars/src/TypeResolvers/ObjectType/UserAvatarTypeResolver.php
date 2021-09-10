<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType\UserAvatarTypeDataLoader;

class UserAvatarTypeResolver extends AbstractObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserAvatar';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('User avatar', 'user-avatars');
    }

    public function getID(object $resultItem): string | int | null
    {
        /** @var UserAvatar */
        $userAvatar = $resultItem;
        return $userAvatar->id;
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return UserAvatarTypeDataLoader::class;
    }
}
