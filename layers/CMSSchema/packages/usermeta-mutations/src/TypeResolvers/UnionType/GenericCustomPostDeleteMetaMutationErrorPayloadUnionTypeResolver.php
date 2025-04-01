<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\GenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractUserDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader $genericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): GenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $genericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericUserDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a user (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
