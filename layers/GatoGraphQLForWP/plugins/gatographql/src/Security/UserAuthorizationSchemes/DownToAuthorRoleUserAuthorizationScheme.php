<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\AbstractByRoleUserAuthorizationScheme;

class DownToAuthorRoleUserAuthorizationScheme extends AbstractByRoleUserAuthorizationScheme
{
    /**
     * @return string[]
     */
    protected function getSchemaEditorAccessRoles(): array
    {
        return [
            'administrator',
            'editor',
            'author',
        ];
    }

    public function getPriority(): int
    {
        return 5;
    }

    public function getSchemaEditorAccessMinimumRequiredCapability(): string
    {
        return 'publish_posts';
    }
}
