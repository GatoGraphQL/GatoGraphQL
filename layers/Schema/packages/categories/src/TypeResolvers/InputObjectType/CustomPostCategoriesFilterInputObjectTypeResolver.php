<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers\InputObjectType;

class CustomPostCategoriesFilterInputObjectTypeResolver extends AbstractCategoriesFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostCategoriesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter categories from a custom post', 'categories');
    }
}
