<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\DefaultUserAuthorizationSchemeTagInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use InvalidArgumentException;

class UserAuthorizationSchemeRegistry implements UserAuthorizationSchemeRegistryInterface
{
    /**
     * @var array<string,UserAuthorizationSchemeInterface>
     */
    protected array $userAuthorizationSchemes = [];
    protected ?UserAuthorizationSchemeInterface $defaultUserAuthorizationScheme = null;

    public function addUserAuthorizationScheme(
        UserAuthorizationSchemeInterface $userAuthorizationScheme
    ): void {
        $this->userAuthorizationSchemes[$userAuthorizationScheme->getName()] = $userAuthorizationScheme;
        if ($userAuthorizationScheme instanceof DefaultUserAuthorizationSchemeTagInterface) {
            $this->defaultUserAuthorizationScheme = $userAuthorizationScheme;
        }
    }

    /**
     * @return UserAuthorizationSchemeInterface[]
     */
    public function getUserAuthorizationSchemes(): array
    {
        return array_values($this->userAuthorizationSchemes);
    }

    public function getUserAuthorizationScheme(string $name): UserAuthorizationSchemeInterface
    {
        if (!isset($this->userAuthorizationSchemes[$name])) {
            throw new InvalidArgumentException(sprintf(
                \__('User authorization scheme with name \'%s\' does not exist', 'graphql-api'),
                $name
            ));
        }
        return $this->userAuthorizationSchemes[$name];
    }

    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface
    {
        if ($this->defaultUserAuthorizationScheme === null) {
            throw new InvalidArgumentException(
                \__('No default user authorization scheme has been set', 'graphql-api')
            );
        }
        return $this->defaultUserAuthorizationScheme;
    }
}
