<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomyTermByOneofInputObjectTypeResolver;

class TaxonomyTermByOneofInputObjectTypeResolver extends AbstractTaxonomyTermByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyTermByFilterInput';
    }
}
