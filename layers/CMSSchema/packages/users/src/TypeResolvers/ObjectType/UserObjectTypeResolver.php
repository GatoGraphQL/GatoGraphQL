<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Users\RelationalTypeDataLoaders\ObjectType\UserTypeDataLoader;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

class UserObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?UserTypeDataLoader $userTypeDataLoader = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    final public function setUserTypeDataLoader(UserTypeDataLoader $userTypeDataLoader): void
    {
        $this->userTypeDataLoader = $userTypeDataLoader;
    }
    final protected function getUserTypeDataLoader(): UserTypeDataLoader
    {
        return $this->userTypeDataLoader ??= $this->instanceManager->getInstance(UserTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'User';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a user', 'users');
    }

    public function getID(object $object): string | int | null
    {
        $user = $object;
        return $this->getUserTypeAPI()->getUserID($user);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserTypeDataLoader();
    }
}
