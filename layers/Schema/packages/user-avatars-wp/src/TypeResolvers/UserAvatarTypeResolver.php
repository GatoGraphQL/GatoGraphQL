<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatarsWP\TypeResolvers;

use PoPSchema\UserAvatarsWP\TypeDataLoaders\UserAvatarTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class UserAvatarTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserAvatar';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('User roles', 'user-avatars');
    }

    public function getID(object $resultItem): string | int | null
    {
        $role = $resultItem;
        return $role->name;
    }

    public function getTypeDataLoaderClass(): string
    {
        return UserAvatarTypeDataLoader::class;
    }
}
