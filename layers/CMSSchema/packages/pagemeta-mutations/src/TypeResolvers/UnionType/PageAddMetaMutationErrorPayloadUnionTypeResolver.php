<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageAddMetaMutationErrorPayloadUnionTypeDataLoader $postAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageAddMetaMutationErrorPayloadUnionTypeDataLoader(): PageAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $postAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postAddMetaMutationErrorPayloadUnionTypeDataLoader = $postAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
