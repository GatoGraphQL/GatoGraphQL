<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeResolvers\Object;

use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;
use PoPSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPSchema\UserAvatars\TypeDataLoaders\UserAvatarTypeDataLoader;

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
