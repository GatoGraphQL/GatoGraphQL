<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomiesFilterInputObjectTypeResolver;

abstract class AbstractCategoriesFilterInputObjectTypeResolver extends AbstractTaxonomiesFilterInputObjectTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter categories', 'categories');
    }

    protected function addParentIDInputField(): bool
    {
        return true;
    }
}
