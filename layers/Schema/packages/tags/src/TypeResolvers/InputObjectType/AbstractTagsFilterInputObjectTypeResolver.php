<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeResolvers\InputObjectType;

use PoPSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomiesFilterInputObjectTypeResolver;

abstract class AbstractTagsFilterInputObjectTypeResolver extends AbstractTaxonomiesFilterInputObjectTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter tags', 'tags');
    }

    protected function addParentIDInputField(): bool
    {
        return false;
    }
}
