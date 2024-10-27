<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

abstract class AbstractPostObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getPostTypeAPI()->isInstanceOfPostType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getPostTypeAPI()->postExists($objectID);
    }
}
