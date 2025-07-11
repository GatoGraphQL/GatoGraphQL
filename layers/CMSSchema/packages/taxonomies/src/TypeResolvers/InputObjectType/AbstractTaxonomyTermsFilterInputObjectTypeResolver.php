<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomiesFilterInputObjectTypeResolver;

abstract class AbstractTaxonomyTermsFilterInputObjectTypeResolver extends AbstractTaxonomiesFilterInputObjectTypeResolver implements TaxonomyTermsFilterInputObjectTypeResolverInterface
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter taxonomy terms', 'taxonomies');
    }

    protected function addParentIDInputField(): bool
    {
        return false;
    }
}
