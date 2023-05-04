<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\UserAuthorizationException;
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
        $this->userAuthorizationSchemes[$userAuthorizationScheme->getName()] = $userAuthorizationScheme;
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

    /**
     * @throws UserAuthorizationException When the scheme is not registered
     */
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
            // They are ordered by priority => get the first item
            $userAuthorizationSchemes = $this->getUserAuthorizationSchemes();
            $this->defaultUserAuthorizationScheme = $userAuthorizationSchemes[0];
        }
        return $this->defaultUserAuthorizationScheme;
    }
}
