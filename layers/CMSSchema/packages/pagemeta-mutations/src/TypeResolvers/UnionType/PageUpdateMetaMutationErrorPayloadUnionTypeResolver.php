<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader $postUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $postUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $postUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
