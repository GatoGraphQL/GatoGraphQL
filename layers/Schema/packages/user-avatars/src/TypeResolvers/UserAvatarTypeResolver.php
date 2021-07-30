<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeResolvers;

use PoPSchema\UserAvatars\TypeDataLoaders\UserAvatarTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

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
        $avatar = $resultItem;
        return $avatar->src;
    }

    public function getTypeDataLoaderClass(): string
    {
        return UserAvatarTypeDataLoader::class;
    }
}
