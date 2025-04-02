<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateUserMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateUserMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateUserMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a user', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateUserMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
