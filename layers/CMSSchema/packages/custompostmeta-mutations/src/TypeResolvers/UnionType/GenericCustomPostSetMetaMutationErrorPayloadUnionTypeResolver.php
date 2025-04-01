<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader $genericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader = $genericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCustomPostSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a custom post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
