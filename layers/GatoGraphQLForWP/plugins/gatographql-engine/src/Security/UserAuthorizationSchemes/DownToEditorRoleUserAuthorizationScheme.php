<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\AbstractByRoleUserAuthorizationScheme;

class DownToEditorRoleUserAuthorizationScheme extends AbstractByRoleUserAuthorizationScheme
{
    /**
     * @return string[]
     */
    protected function getSchemaEditorAccessRoles(): array
    {
        return [
            'administrator',
            'editor',
        ];
    }

    public function getPriority(): int
    {
        return 4;
    }

    public function getSchemaEditorAccessMinimumRequiredCapability(): string
    {
        return 'edit_others_posts';
    }
}
