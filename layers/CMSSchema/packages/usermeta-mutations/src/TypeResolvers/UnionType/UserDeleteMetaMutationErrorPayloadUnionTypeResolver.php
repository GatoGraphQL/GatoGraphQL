<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\UserDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractUserDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?UserDeleteMetaMutationErrorPayloadUnionTypeDataLoader $userDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): UserDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->userDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var UserDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $userDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(UserDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->userDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $userDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->userDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a user (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
