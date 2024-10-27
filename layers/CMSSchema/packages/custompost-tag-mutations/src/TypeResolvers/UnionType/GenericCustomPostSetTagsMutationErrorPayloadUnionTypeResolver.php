<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostTagMutations\RelationalTypeDataLoaders\UnionType\GenericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver extends AbstractGenericTagsMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader $genericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader(): GenericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader */
            $genericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader = $genericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCustomPostSetTagsMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting tags on a custom post (using nested mutations)', 'posttag-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCustomPostSetTagsMutationErrorPayloadUnionTypeDataLoader();
    }
}
