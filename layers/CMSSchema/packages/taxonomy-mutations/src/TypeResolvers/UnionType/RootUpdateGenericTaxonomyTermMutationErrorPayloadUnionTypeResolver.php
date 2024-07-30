<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractRootUpdateTaxonomyMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateTaxonomyMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader(RootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateTaxonomyTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a taxonomy term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericTaxonomyTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
