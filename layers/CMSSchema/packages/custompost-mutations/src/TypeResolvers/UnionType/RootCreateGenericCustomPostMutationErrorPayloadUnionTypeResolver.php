<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootCreateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\RootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader(RootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a custom post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
