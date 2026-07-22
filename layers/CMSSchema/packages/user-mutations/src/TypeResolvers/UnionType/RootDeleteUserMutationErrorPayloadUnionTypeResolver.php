<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\UnionType\RootDeleteUserMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteUserMutationErrorPayloadUnionTypeResolver extends AbstractDeleteUserMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteUserMutationErrorPayloadUnionTypeDataLoader $rootDeleteUserMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteUserMutationErrorPayloadUnionTypeDataLoader(): RootDeleteUserMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteUserMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteUserMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteUserMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteUserMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteUserMutationErrorPayloadUnionTypeDataLoader = $rootDeleteUserMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteUserMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteUserMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a user', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteUserMutationErrorPayloadUnionTypeDataLoader();
    }
}
