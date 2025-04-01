<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\GenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericUserAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractUserAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader $genericUserAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader(): GenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericUserAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericUserAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericUserAddMetaMutationErrorPayloadUnionTypeDataLoader = $genericUserAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericUserAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericUserAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a user (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
