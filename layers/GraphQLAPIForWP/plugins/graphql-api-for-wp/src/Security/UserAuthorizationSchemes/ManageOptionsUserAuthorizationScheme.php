<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

class ManageOptionsUserAuthorizationScheme extends AbstractUserAuthorizationScheme implements DefaultUserAuthorizationSchemeServiceTagInterface
{
    public function getSchemaEditorAccessCapability(): string
    {
        return UserAuthorizationSchemes::ADMIN_ONLY;
    }

    public function getDescription(): string
    {
        return \__('Admin user(s) only', 'graphql-api');
    }
}
