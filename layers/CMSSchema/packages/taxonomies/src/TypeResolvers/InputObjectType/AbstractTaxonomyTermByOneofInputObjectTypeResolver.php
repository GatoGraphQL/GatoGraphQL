<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\Constants\InputProperties;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomyByInputObjectTypeResolver;

abstract class AbstractTaxonomyTermByOneofInputObjectTypeResolver extends AbstractTaxonomyByInputObjectTypeResolver
{
    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->__('Query by taxonomyTerm ID', 'taxonomies'),
            InputProperties::SLUG => $this->__('Query by taxonomyTerm slug', 'taxonomies'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    protected function getTypeDescriptionTaxonomyEntity(): string
    {
        return $this->__('a taxonomy term', 'taxonomies');
    }
}
