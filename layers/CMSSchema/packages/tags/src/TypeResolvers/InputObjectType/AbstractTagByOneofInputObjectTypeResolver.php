<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomyByInputObjectTypeResolver;

abstract class AbstractTagByOneofInputObjectTypeResolver extends AbstractTaxonomyByInputObjectTypeResolver
{
    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by tag ID', 'tags'),
            'slug' => $this->__('Query by tag slug', 'tags'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    protected function getTypeDescriptionTaxonomyEntity(): string
    {
        return $this->__('a tag', 'tags');
    }
}
