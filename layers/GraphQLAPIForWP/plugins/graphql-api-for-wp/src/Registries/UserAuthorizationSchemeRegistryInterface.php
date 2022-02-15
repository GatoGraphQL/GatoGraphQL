<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\UserAuthorizationException;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;

interface UserAuthorizationSchemeRegistryInterface
{
    public function addUserAuthorizationScheme(UserAuthorizationSchemeInterface $userAuthorizationScheme): void;
    /**
     * @return UserAuthorizationSchemeInterface[]
     */
    public function getUserAuthorizationSchemes(): array;
    /**
     * @throws UserAuthorizationException When the scheme is not registered
     */
    public function getUserAuthorizationScheme(string $name): UserAuthorizationSchemeInterface;
    /**
     * @throws UserAuthorizationException When no default object has been set
     */
    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface;
}
