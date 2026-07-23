<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\UnionType\RootCreateUserMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateUserMutationErrorPayloadUnionTypeResolver extends AbstractCreateUserMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateUserMutationErrorPayloadUnionTypeDataLoader $rootCreateUserMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootCreateUserMutationErrorPayloadUnionTypeDataLoader(): RootCreateUserMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateUserMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateUserMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateUserMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateUserMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateUserMutationErrorPayloadUnionTypeDataLoader = $rootCreateUserMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateUserMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateUserMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a user', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateUserMutationErrorPayloadUnionTypeDataLoader();
    }
}
