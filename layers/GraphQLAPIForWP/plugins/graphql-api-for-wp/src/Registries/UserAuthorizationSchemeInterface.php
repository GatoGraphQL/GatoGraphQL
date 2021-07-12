<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use InvalidArgumentException;
use GraphQLAPI\GraphQLAPI\Services\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;

interface UserAuthorizationSchemeRegistryInterface
{
    public function addUserAuthorizationScheme(UserAuthorizationSchemeInterface $userAuthorizationScheme): void;
    /**
     * @return array<string,UserAuthorizationSchemeInterface>
     */
    public function getUserAuthorizationSchemes(): array;
    /**
     * @throws InvalidArgumentException If module does not exist
     */
    public function getUserAuthorizationScheme(string $name): UserAuthorizationSchemeInterface;
}
