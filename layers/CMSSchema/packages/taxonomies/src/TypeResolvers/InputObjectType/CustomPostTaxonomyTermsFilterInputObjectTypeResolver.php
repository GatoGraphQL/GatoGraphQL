<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

class CustomPostTaxonomyTermsFilterInputObjectTypeResolver extends AbstractTaxonomyTermsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostTaxonomyTermsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter taxonomyTerms from a custom post', 'taxonomies');
    }
}
