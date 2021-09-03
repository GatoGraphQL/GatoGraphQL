<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;

abstract class AbstractPostTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function getObjectTypeResolverClass(): string
    {
        return PostTypeResolver::class;
    }

    public function isInstanceOfType(object $object): bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->isInstanceOfPostType($object);
    }

    public function isIDOfType(string | int $resultItemID): bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->postExists($resultItemID);
    }
}
