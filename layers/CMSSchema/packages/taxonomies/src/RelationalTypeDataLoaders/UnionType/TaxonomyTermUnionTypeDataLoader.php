<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\UnionType\TaxonomyTermUnionTypeResolver;

class TaxonomyTermUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?TaxonomyTermUnionTypeResolver $taxonomyTermUnionTypeResolver = null;

    final protected function getTaxonomyTermUnionTypeResolver(): TaxonomyTermUnionTypeResolver
    {
        if ($this->taxonomyTermUnionTypeResolver === null) {
            /** @var TaxonomyTermUnionTypeResolver */
            $taxonomyTermUnionTypeResolver = $this->instanceManager->getInstance(TaxonomyTermUnionTypeResolver::class);
            $this->taxonomyTermUnionTypeResolver = $taxonomyTermUnionTypeResolver;
        }
        return $this->taxonomyTermUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getTaxonomyTermUnionTypeResolver();
    }
}
