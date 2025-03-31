<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootAddCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\RootAddPostMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddPostMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootAddCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddPostMetaMutationErrorPayloadUnionTypeDataLoader $rootAddPostMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootAddPostMetaMutationErrorPayloadUnionTypeDataLoader(): RootAddPostMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddPostMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddPostMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootAddPostMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddPostMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddPostMetaMutationErrorPayloadUnionTypeDataLoader = $rootAddPostMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddPostMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddPostMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddPostMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
