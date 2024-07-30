<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractRootUpdateTaxonomyMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateTaxonomyMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader(RootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateTaxonomyMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a taxonomy', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader();
    }
}
