<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\GenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractUserUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader $genericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): GenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $genericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericUserUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a user (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
