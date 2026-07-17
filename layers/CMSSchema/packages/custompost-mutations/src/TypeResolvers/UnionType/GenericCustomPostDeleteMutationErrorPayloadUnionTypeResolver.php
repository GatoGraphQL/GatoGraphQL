<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\GenericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostDeleteMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader $genericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader(): GenericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader */
            $genericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader = $genericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostDeleteMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a custom post', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCustomPostDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}
