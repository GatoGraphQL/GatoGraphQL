<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootCreateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType\RootCreatePageMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreatePageMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreatePageMutationErrorPayloadUnionTypeDataLoader $rootCreatePageMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreatePageMutationErrorPayloadUnionTypeDataLoader(RootCreatePageMutationErrorPayloadUnionTypeDataLoader $rootCreatePageMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreatePageMutationErrorPayloadUnionTypeDataLoader = $rootCreatePageMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreatePageMutationErrorPayloadUnionTypeDataLoader(): RootCreatePageMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreatePageMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreatePageMutationErrorPayloadUnionTypeDataLoader */
            $rootCreatePageMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreatePageMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreatePageMutationErrorPayloadUnionTypeDataLoader = $rootCreatePageMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreatePageMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreatePageMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a page', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreatePageMutationErrorPayloadUnionTypeDataLoader();
    }
}
