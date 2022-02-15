<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\DefaultUserAuthorizationSchemeServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use PoP\Root\Exception\GenericException;

class UserAuthorizationSchemeRegistry implements UserAuthorizationSchemeRegistryInterface
{
    /**
     * @var array<string,UserAuthorizationSchemeInterface>
     */
    protected array $userAuthorizationSchemes = [];
    private ?UserAuthorizationSchemeInterface $defaultUserAuthorizationScheme = null;

    public function addUserAuthorizationScheme(
        UserAuthorizationSchemeInterface $userAuthorizationScheme
    ): void {
        if ($userAuthorizationScheme instanceof DefaultUserAuthorizationSchemeServiceTagInterface) {
            $this->defaultUserAuthorizationScheme = $userAuthorizationScheme;
            // Place the default one at the top
            // @see http://www.mendoweb.be/blog/php-array_unshift-key-array_unshift-associative-array/
            $this->userAuthorizationSchemes = [$userAuthorizationScheme->getName() => $userAuthorizationScheme] + $this->userAuthorizationSchemes;
        } else {
            // Place at the end
            $this->userAuthorizationSchemes[$userAuthorizationScheme->getName()] = $userAuthorizationScheme;
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
            throw new GenericException(sprintf(
                \__('User authorization scheme with name \'%s\' does not exist', 'graphql-api'),
                $name
            ));
        }
        return $this->userAuthorizationSchemes[$name];
    }

    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface
    {
        if ($this->defaultUserAuthorizationScheme === null) {
            throw new GenericException(
                \__('No default user authorization scheme has been set', 'graphql-api')
            );
        }
        return $this->defaultUserAuthorizationScheme;
    }
}
