<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomiesFilterInputObjectTypeResolver;

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
