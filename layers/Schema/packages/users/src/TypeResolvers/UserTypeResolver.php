<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\TypeDataLoaders\UserTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class UserTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'User';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a user', 'users');
    }

    public function getID(object $resultItem): string | int
    {
        $cmsusersresolver = \PoPSchema\Users\ObjectPropertyResolverFactory::getInstance();
        $user = $resultItem;
        return $cmsusersresolver->getUserId($user);
    }

    public function getTypeDataLoaderClass(): string
    {
        return UserTypeDataLoader::class;
    }
}
