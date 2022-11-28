<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\RootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader $rootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader(RootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader $rootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'RootCreateCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
