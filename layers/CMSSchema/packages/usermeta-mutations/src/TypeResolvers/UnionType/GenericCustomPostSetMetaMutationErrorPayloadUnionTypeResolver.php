<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\GenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericUserSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractUserSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader $genericUserSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader(): GenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericUserSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericUserSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericUserSetMetaMutationErrorPayloadUnionTypeDataLoader = $genericUserSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericUserSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericUserSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a user (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
