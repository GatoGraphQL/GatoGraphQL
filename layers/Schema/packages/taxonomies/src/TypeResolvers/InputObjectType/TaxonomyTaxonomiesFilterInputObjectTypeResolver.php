<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\TypeResolvers\InputObjectType;

class TaxonomyTaxonomiesFilterInputObjectTypeResolver extends AbstractTaxonomiesFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyTaxonomiesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter child taxonomies', 'taxonomies');
    }

    protected function addParentIDInputField(): bool
    {
        return false;
    }
}
