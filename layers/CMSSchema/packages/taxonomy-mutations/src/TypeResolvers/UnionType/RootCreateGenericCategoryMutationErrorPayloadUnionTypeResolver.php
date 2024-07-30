<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractRootCreateTaxonomyMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\UnionType\RootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateGenericTaxonomyMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateTaxonomyMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader(RootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader(): RootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateTaxonomyMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a taxonomy', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateGenericTaxonomyMutationErrorPayloadUnionTypeDataLoader();
    }
}
