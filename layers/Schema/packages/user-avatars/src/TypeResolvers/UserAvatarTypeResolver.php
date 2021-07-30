<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPSchema\UserAvatars\TypeDataLoaders\UserAvatarTypeDataLoader;

class UserAvatarTypeResolver extends AbstractTypeResolver
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

    public function getTypeDataLoaderClass(): string
    {
        return UserAvatarTypeDataLoader::class;
    }
}
