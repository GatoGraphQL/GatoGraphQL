<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\RootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader $rootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader(RootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader $rootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'RootUpdateCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
