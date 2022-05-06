<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

class ManageOptionsUserAuthorizationScheme extends AbstractUserAuthorizationScheme implements DefaultUserAuthorizationSchemeServiceTagInterface
{
    public function getSchemaEditorAccessCapability(): string
    {
        return 'manage_options';
    }

    public function getDescription(): string
    {
        return \__('Admin user(s) only', 'graphql-api');
    }
}
