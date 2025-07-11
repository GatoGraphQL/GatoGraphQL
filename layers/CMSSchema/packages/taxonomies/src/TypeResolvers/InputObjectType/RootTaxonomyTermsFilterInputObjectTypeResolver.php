<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

class RootTaxonomyTermsFilterInputObjectTypeResolver extends AbstractTaxonomyTermsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootTaxonomyTermsFilterInput';
    }
}
