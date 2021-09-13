<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers\ObjectType;

use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoPSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;

abstract class AbstractCategoryObjectTypeResolver extends AbstractTaxonomyObjectTypeResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a category, added to a custom post', 'categories');
    }

    public function getID(object $resultItem): string | int | null
    {
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $category = $resultItem;
        return $categoryTypeAPI->getCategoryID($category);
    }
}
