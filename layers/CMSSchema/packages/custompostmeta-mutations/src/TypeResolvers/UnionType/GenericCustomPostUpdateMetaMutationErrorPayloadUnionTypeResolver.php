<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader $genericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $genericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCustomPostUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a custom post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
