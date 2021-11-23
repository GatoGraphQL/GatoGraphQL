<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\TypeResolvers\InputObjectType;

class RootTaxonomiesFilterInputObjectTypeResolver extends AbstractTaxonomiesFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootTaxonomiesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter taxonomies', 'taxonomies');
    }
}
