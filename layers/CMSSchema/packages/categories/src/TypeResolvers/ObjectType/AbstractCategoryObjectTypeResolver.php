<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers\ObjectType;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;

abstract class AbstractCategoryObjectTypeResolver extends AbstractTaxonomyObjectTypeResolver implements CategoryObjectTypeResolverInterface
{
    abstract public function getCategoryTypeAPI(): CategoryTypeAPIInterface;

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a category, added to a custom post', 'categories');
    }

    public function getID(object $object): string | int | null
    {
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $category = $object;
        return $categoryTypeAPI->getCategoryID($category);
    }
}
