<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\UserIsNotLoggedInErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserIsNotLoggedInErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserIsNotLoggedInErrorPayloadObjectTypeDataLoader $userIsNotLoggedInErrorPayloadObjectTypeDataLoader = null;

    final public function setUserIsNotLoggedInErrorPayloadObjectTypeDataLoader(UserIsNotLoggedInErrorPayloadObjectTypeDataLoader $userIsNotLoggedInErrorPayloadObjectTypeDataLoader): void
    {
        $this->userIsNotLoggedInErrorPayloadObjectTypeDataLoader = $userIsNotLoggedInErrorPayloadObjectTypeDataLoader;
    }
    final protected function getUserIsNotLoggedInErrorPayloadObjectTypeDataLoader(): UserIsNotLoggedInErrorPayloadObjectTypeDataLoader
    {
        /** @var UserIsNotLoggedInErrorPayloadObjectTypeDataLoader */
        return $this->userIsNotLoggedInErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(UserIsNotLoggedInErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'UserIsNotLoggedInErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user is not logged-in"', 'user-state-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserIsNotLoggedInErrorPayloadObjectTypeDataLoader();
    }
}
