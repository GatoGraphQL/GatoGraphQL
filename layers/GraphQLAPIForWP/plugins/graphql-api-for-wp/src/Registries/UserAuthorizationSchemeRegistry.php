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

    public function getDefaultSchemaEditorAccessCapability(): string
    {
        return 'admin';
    }
}
