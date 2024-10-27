<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a custom post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
