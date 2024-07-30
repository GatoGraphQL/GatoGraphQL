<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TaxonomyMutations\TypeResolvers\UnionType\AbstractTaxonomyUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\UnionType\GenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericTaxonomyUpdateMutationErrorPayloadUnionTypeResolver extends AbstractTaxonomyUpdateMutationErrorPayloadUnionTypeResolver
{
    private ?GenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader $genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setGenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader(GenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader $genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader = $genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getGenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader(): GenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader */
            $genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader = $genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TaxonomyUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a taxonomy (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericTaxonomyUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
