<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Exception\UserAuthorizationException;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;

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
    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface;
}
