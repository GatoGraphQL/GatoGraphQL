<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes;

class AdministratorRoleUserAuthorizationScheme extends AbstractByRoleUserAuthorizationScheme
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

    public function getSchemaEditorAccessMinimumRequiredCapability(): string
    {
        return 'manage_options';
    }
}
