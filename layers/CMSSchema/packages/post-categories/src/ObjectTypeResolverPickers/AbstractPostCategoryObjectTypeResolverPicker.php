<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ObjectTypeResolverPickers;

use PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface;
use PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerTrait;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractPostCategoryObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements CategoryObjectTypeResolverPickerInterface
{
    use CategoryObjectTypeResolverPickerTrait;

    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getPostCategoryObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getPostCategoryTypeAPI()->isInstanceOfCategoryType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getPostCategoryTypeAPI()->categoryExists($objectID);
    }

    /**
     * @return string[]
     */
    public function getCategoryTaxonomies(): array
    {
        return $this->getPostCategoryTypeAPI()->getRegisteredPostCategoryTaxonomyNames();
    }
}
