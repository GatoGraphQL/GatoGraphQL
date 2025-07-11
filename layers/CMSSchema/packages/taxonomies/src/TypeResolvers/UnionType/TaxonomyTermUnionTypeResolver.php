<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\Taxonomies\RelationalTypeDataLoaders\UnionType\TaxonomyTermUnionTypeDataLoader;
use PoPCMSSchema\Taxonomies\TypeResolvers\InterfaceType\TaxonomyTermInterfaceTypeResolver;

class TaxonomyTermUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?TaxonomyTermUnionTypeDataLoader $taxonomyTermUnionTypeDataLoader = null;
    private ?TaxonomyTermInterfaceTypeResolver $taxonomyTermInterfaceTypeResolver = null;

    final protected function getTaxonomyTermUnionTypeDataLoader(): TaxonomyTermUnionTypeDataLoader
    {
        if ($this->taxonomyTermUnionTypeDataLoader === null) {
            /** @var TaxonomyTermUnionTypeDataLoader */
            $taxonomyTermUnionTypeDataLoader = $this->instanceManager->getInstance(TaxonomyTermUnionTypeDataLoader::class);
            $this->taxonomyTermUnionTypeDataLoader = $taxonomyTermUnionTypeDataLoader;
        }
        return $this->taxonomyTermUnionTypeDataLoader;
    }
    final protected function getTaxonomyTermInterfaceTypeResolver(): TaxonomyTermInterfaceTypeResolver
    {
        if ($this->taxonomyTermInterfaceTypeResolver === null) {
            /** @var TaxonomyTermInterfaceTypeResolver */
            $taxonomyTermInterfaceTypeResolver = $this->instanceManager->getInstance(TaxonomyTermInterfaceTypeResolver::class);
            $this->taxonomyTermInterfaceTypeResolver = $taxonomyTermInterfaceTypeResolver;
        }
        return $this->taxonomyTermInterfaceTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'TaxonomyTermUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'taxonomy term\' type resolvers', 'taxonomyTerms');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTaxonomyTermUnionTypeDataLoader();
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getTaxonomyTermInterfaceTypeResolver(),
        ];
    }
}
