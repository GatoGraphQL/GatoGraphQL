<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\UserIsLoggedInErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserIsLoggedInErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserIsLoggedInErrorPayloadObjectTypeDataLoader $userIsLoggedInErrorPayloadObjectTypeDataLoader = null;

    final public function setUserIsLoggedInErrorPayloadObjectTypeDataLoader(UserIsLoggedInErrorPayloadObjectTypeDataLoader $userIsLoggedInErrorPayloadObjectTypeDataLoader): void
    {
        $this->userIsLoggedInErrorPayloadObjectTypeDataLoader = $userIsLoggedInErrorPayloadObjectTypeDataLoader;
    }
    final protected function getUserIsLoggedInErrorPayloadObjectTypeDataLoader(): UserIsLoggedInErrorPayloadObjectTypeDataLoader
    {
        /** @var UserIsLoggedInErrorPayloadObjectTypeDataLoader */
        return $this->userIsLoggedInErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(UserIsLoggedInErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'UserIsLoggedInErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user is already logged-in"', 'user-state-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserIsLoggedInErrorPayloadObjectTypeDataLoader();
    }
}
