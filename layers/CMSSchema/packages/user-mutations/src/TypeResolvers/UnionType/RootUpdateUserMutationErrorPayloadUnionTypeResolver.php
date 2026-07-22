<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\UnionType\RootUpdateUserMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateUserMutationErrorPayloadUnionTypeResolver extends AbstractUpdateUserMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateUserMutationErrorPayloadUnionTypeDataLoader $rootUpdateUserMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateUserMutationErrorPayloadUnionTypeDataLoader(): RootUpdateUserMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateUserMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateUserMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateUserMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateUserMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateUserMutationErrorPayloadUnionTypeDataLoader = $rootUpdateUserMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateUserMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateUserMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a user', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateUserMutationErrorPayloadUnionTypeDataLoader();
    }
}
