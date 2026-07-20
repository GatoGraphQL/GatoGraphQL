<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootDeleteCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType\RootDeletePageMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeletePageMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeletePageMutationErrorPayloadUnionTypeDataLoader $rootDeletePageMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeletePageMutationErrorPayloadUnionTypeDataLoader(): RootDeletePageMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeletePageMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeletePageMutationErrorPayloadUnionTypeDataLoader */
            $rootDeletePageMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeletePageMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeletePageMutationErrorPayloadUnionTypeDataLoader = $rootDeletePageMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeletePageMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeletePageMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a page', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeletePageMutationErrorPayloadUnionTypeDataLoader();
    }
}
