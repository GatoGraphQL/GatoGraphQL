<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Users\RelationalTypeDataLoaders\ObjectType\UserTypeDataLoader;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class UserObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected UserTypeAPIInterface $userTypeAPI;
    protected UserTypeDataLoader $userTypeDataLoader;

    #[Required]
    public function autowireUserObjectTypeResolver(
        UserTypeAPIInterface $userTypeAPI,
        UserTypeDataLoader $userTypeDataLoader,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
        $this->userTypeDataLoader = $userTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'User';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a user', 'users');
    }

    public function getID(object $object): string | int | null
    {
        $user = $object;
        return $this->userTypeAPI->getUserId($user);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->userTypeDataLoader;
    }
}
