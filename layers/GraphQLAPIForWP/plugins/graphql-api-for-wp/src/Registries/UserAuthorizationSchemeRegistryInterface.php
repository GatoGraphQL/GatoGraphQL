<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use InvalidArgumentException;

interface UserAuthorizationSchemeRegistryInterface
{
    public function addUserAuthorizationScheme(UserAuthorizationSchemeInterface $userAuthorizationScheme): void;
    /**
     * @return UserAuthorizationSchemeInterface[]
     */
    public function getUserAuthorizationSchemes(): array;
    /**
     * @throws InvalidArgumentException When the scheme is not registered
     */
    public function getUserAuthorizationScheme(string $name): UserAuthorizationSchemeInterface;
    /**
     * @throws InvalidArgumentException When no default object has been set
     */
    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface;
}
