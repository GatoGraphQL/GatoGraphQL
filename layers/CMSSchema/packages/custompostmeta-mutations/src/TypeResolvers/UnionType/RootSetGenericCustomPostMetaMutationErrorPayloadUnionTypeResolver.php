<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootSetCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader $rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetGenericCustomPostMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a custom post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
