<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use InvalidArgumentException;

interface UserAuthorizationSchemeRegistryInterface
{
    public function addSchemaEditorAccessCapability(UserAuthorizationSchemeInterface $userAuthorizationScheme): void;
    /**
     * @throws InvalidArgumentException When the scheme is not registered
     */
    public function getSchemaEditorAccessCapability(string $userAuthorizationSchemeName): string;
    public function getDefaultSchemaEditorAccessCapability(): string;
}
