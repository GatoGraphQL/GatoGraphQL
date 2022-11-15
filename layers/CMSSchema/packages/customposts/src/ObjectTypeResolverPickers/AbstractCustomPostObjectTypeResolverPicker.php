<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

abstract class AbstractCustomPostObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?CustomPostObjectTypeResolver $customPostObjectTypeResolver = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final public function setCustomPostObjectTypeResolver(CustomPostObjectTypeResolver $customPostObjectTypeResolver): void
    {
        $this->customPostObjectTypeResolver = $customPostObjectTypeResolver;
    }
    final protected function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolver
    {
        /** @var CustomPostObjectTypeResolver */
        return $this->customPostObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
    }
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCustomPostObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getCustomPostTypeAPI()->isInstanceOfCustomPostType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getCustomPostTypeAPI()->customPostExists($objectID);
    }
}
