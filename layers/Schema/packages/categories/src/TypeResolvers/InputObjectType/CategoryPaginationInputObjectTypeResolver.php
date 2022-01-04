<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers\InputObjectType;

use PoPSchema\Categories\Component;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class CategoryPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CategoryPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate categories', 'categories');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getCategoryListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getCategoryListMaxLimit();
    }
}
