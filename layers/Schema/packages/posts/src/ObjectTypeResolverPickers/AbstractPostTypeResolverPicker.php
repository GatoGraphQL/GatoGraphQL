<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

abstract class AbstractPostTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function getObjectTypeResolverClass(): string
    {
        return PostObjectTypeResolver::class;
    }

    public function isInstanceOfType(object $object): bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->isInstanceOfPostType($object);
    }

    public function isIDOfType(string | int $objectID): bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->postExists($objectID);
    }
}
