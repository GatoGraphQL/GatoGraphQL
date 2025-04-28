<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\PageSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PageSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PageSetMetaMutationErrorPayloadUnionTypeDataLoader $postSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPageSetMetaMutationErrorPayloadUnionTypeDataLoader(): PageSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PageSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $postSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PageSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postSetMetaMutationErrorPayloadUnionTypeDataLoader = $postSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PageSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
