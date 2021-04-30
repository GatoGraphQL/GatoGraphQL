<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers;

use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoPSchema\Taxonomies\TypeResolvers\AbstractTaxonomyTypeResolver;

abstract class AbstractCategoryTypeResolver extends AbstractTaxonomyTypeResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a category, added to a custom post', 'categories');
    }

    public function getID(object $resultItem): string | int
    {
        $categoryTypeAPI = $this->getTypeAPI();
        $category = $resultItem;
        return $categoryTypeAPI->getCategoryID($category);
    }
}
