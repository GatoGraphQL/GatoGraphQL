<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a custom post', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
