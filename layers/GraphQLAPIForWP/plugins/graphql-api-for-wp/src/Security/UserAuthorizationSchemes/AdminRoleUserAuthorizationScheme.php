<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

class AdminRoleUserAuthorizationScheme extends AbstractByRoleUserAuthorizationScheme
{
    /**
     * @return string[]
     */
    protected function getSchemaEditorAccessRoles(): array
    {
        return [
            'administrator',
        ];
    }

    public function getPriority(): int
    {
        return 3;
    }
}
