<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractRootCreateTaxonomyMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\UnionType\RootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateTaxonomyMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader(RootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader(): RootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateTaxonomyTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a taxonomy term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
