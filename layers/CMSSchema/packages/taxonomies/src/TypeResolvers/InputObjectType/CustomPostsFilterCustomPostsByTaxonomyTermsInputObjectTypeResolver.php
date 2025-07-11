<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyTermTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class CustomPostsFilterCustomPostsByTaxonomyTermsInputObjectTypeResolver extends AbstractFilterCustomPostsByTaxonomyTermsInputObjectTypeResolver
{
    private ?TaxonomyTermTaxonomyEnumStringScalarTypeResolver $taxonomyTermTaxonomyEnumStringScalarTypeResolver = null;

    final protected function getTaxonomyTermTaxonomyEnumStringScalarTypeResolver(): TaxonomyTermTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->taxonomyTermTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var TaxonomyTermTaxonomyEnumStringScalarTypeResolver */
            $taxonomyTermTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(TaxonomyTermTaxonomyEnumStringScalarTypeResolver::class);
            $this->taxonomyTermTaxonomyEnumStringScalarTypeResolver = $taxonomyTermTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->taxonomyTermTaxonomyEnumStringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'FilterCustomPostsByTaxonomyTermsInput';
    }

    protected function getTaxonomyTermTaxonomyFilterInput(): InputTypeResolverInterface
    {
        return $this->getTaxonomyTermTaxonomyEnumStringScalarTypeResolver();
    }

    protected function getTaxonomyTermTaxonomyFilterDefaultValue(): mixed
    {
        return null;
    }
}
