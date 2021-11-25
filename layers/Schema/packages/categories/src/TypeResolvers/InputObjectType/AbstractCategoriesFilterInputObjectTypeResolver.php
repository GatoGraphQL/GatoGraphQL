<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers\InputObjectType;

use PoPSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomiesFilterInputObjectTypeResolver;

abstract class AbstractCategoriesFilterInputObjectTypeResolver extends AbstractTaxonomiesFilterInputObjectTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter categories', 'categories');
    }

    protected function addParentIDInputField(): bool
    {
        return true;
    }
}
