<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

class EditPostsUserAuthorizationScheme extends AbstractUserAuthorizationScheme
{
    public function getSchemaEditorAccessCapability(): string
    {
        return 'edit_posts';
    }

    public function getDescription(): string
    {
        return \__('Use same access workflow as for editing posts', 'graphql-api');
    }
}
