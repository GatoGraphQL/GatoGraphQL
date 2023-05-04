<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

class AdminRoleUserAuthorizationScheme extends AbstractUserAuthorizationScheme
{
    public function getSchemaEditorAccessCapability(): string
    {
        return 'manage_options';
    }

    public function getDescription(): string
    {
        return \__('Admin users', 'graphql-api');
    }

    public function getPriority(): int
    {
        return 1;
    }
}
