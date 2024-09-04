<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerInterface;
use PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerTrait;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractPostTagObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements TagObjectTypeResolverPickerInterface
{
    use TagObjectTypeResolverPickerTrait;

    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
    }
    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getPostTagTypeAPI()->isInstanceOfTagType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getPostTagTypeAPI()->tagExists($objectID);
    }

    /**
     * @return string[]
     */
    public function getTagTaxonomies(): array
    {
        return $this->getPostTagTypeAPI()->getRegisteredPostTagTaxonomyNames();
    }
}
