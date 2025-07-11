<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\ObjectTypeResolverPickers;

use PoPCMSSchema\Taxonomies\TypeResolvers\UnionType\TaxonomyTermUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TaxonomyTermUnionGenericTaxonomyTermObjectTypeResolverPicker extends AbstractGenericTaxonomyTermObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            TaxonomyTermUnionTypeResolver::class,
        ];
    }
}
