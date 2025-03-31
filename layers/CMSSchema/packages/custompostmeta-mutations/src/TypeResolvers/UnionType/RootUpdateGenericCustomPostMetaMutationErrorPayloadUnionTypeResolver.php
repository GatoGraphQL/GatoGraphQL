<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateGenericCustomPostMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a custom post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
