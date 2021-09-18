<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

abstract class AbstractPostObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function __construct(
        protected PostObjectTypeResolver $postObjectTypeResolver,
    ) {
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->postObjectTypeResolver;
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
