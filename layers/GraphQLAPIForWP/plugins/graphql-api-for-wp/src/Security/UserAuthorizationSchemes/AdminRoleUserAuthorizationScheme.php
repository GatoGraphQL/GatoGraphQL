<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

class AdminRoleUserAuthorizationScheme extends AbstractByRoleUserAuthorizationScheme
{
    public function getSchemaEditorAccessRole(): string
    {
        return 'administrator';
    }

    public function getPriority(): int
    {
        return 3;
    }
}
