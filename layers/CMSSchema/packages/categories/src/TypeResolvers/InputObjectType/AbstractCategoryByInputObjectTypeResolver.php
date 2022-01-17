<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomyByInputObjectTypeResolver;

abstract class AbstractCategoryByInputObjectTypeResolver extends AbstractTaxonomyByInputObjectTypeResolver
{
    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by category ID', 'categories'),
            'slug' => $this->__('Query by category slug', 'categories'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    protected function getTypeDescriptionTaxonomyEntity(): string
    {
        return $this->__('a category', 'categories');
    }
}
