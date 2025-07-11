<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\EnumType;

class TaxonomyTermTaxonomyEnumStringScalarTypeResolver extends AbstractTaxonomyTermTaxonomyEnumStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyTermTaxonomyEnumString';
    }

    protected function getRegisteredCustomPostTaxonomyTermTaxonomyNames(): ?array
    {
        return null;
    }
}
