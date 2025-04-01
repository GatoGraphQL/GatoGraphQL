<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateUserMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateGenericUserMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a user', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
