<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\UserAuthorizationException;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\DefaultUserAuthorizationSchemeServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;

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
     * Registered UserAuthorizationSchemes, ordered by priority.
     *
     * @return UserAuthorizationSchemeInterface[]
     */
    public function getUserAuthorizationSchemes(): array
    {
        $userAuthorizationSchemes = array_values($this->userAuthorizationSchemes);
        usort(
            $userAuthorizationSchemes,
            fn (UserAuthorizationSchemeInterface $a, UserAuthorizationSchemeInterface $b) => $a->getPriority() <=> $b->getPriority()
        );
        return $userAuthorizationSchemes;
    }

    public function getUserAuthorizationScheme(string $name): UserAuthorizationSchemeInterface
    {
        if (!isset($this->userAuthorizationSchemes[$name])) {
            throw new UserAuthorizationException(sprintf(
                \__('User authorization scheme with name \'%s\' does not exist', 'graphql-api'),
                $name
            ));
        }
        return $this->userAuthorizationSchemes[$name];
    }

    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface
    {
        if ($this->defaultUserAuthorizationScheme === null) {
            throw new UserAuthorizationException(
                \__('No default user authorization scheme has been set', 'graphql-api')
            );
        }
        return $this->defaultUserAuthorizationScheme;
    }
}
