<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader $postDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $postDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $postDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
