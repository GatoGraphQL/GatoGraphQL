<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\Constants\InputProperties;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomyByInputObjectTypeResolver;

abstract class AbstractCategoryByOneofInputObjectTypeResolver extends AbstractTaxonomyByInputObjectTypeResolver
{
    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->__('Query by category ID', 'categories'),
            InputProperties::SLUG => $this->__('Query by category slug', 'categories'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    protected function getTypeDescriptionTaxonomyEntity(): string
    {
        return $this->__('a category', 'categories');
    }
}
