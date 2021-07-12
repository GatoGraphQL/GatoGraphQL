<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use InvalidArgumentException;

class UserAuthorizationSchemeRegistry implements UserAuthorizationSchemeRegistryInterface
{
    /**
     * @var array<string,string>
     */
    protected array $schemaEditorAccessCapabilities = [];
    protected ?UserAuthorizationSchemeInterface $defaultUserAuthorizationScheme;

    public function addSchemaEditorAccessCapability(
        UserAuthorizationSchemeInterface $userAuthorizationScheme
    ): void {
        $this->schemaEditorAccessCapabilities[$userAuthorizationScheme->getName()] = $userAuthorizationScheme->getSchemaEditorAccessCapability();
    }
    /**
     * @throws InvalidArgumentException When the scheme is not registered
     */
    public function getSchemaEditorAccessCapability(string $userAuthorizationSchemeName): string
    {
        if (!isset($this->userAuthorizationSchemes[$userAuthorizationSchemeName])) {
            throw new InvalidArgumentException(sprintf(
                \__('User authorization scheme \'%s\' does not exist', 'graphql-api'),
                $userAuthorizationSchemeName
            ));
        }
        return $this->schemaEditorAccessCapabilities[$userAuthorizationSchemeName];
    }

    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface
    {
        if ($this->defaultUserAuthorizationScheme === null) {
            throw new InvalidArgumentException(
                \__('No default User authorization scheme has been set', 'graphql-api')
            );
        }
        return $this->defaultUserAuthorizationScheme;
    }
}
