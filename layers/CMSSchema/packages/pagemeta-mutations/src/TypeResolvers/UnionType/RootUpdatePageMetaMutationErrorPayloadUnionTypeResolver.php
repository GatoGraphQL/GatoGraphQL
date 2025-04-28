<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdatePageMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdatePageMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a page', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePageMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
