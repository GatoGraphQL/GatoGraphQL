<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes;

use WP_User;

abstract class AbstractByRoleUserAuthorizationScheme extends AbstractUserAuthorizationScheme
{
    public function getName(): string
    {
        return sprintf(
            'access_scheme-roles-%s',
            implode('-', $this->getSchemaEditorAccessRoles())
        );
    }

    public function getDescription(): string
    {
        $schemaEditorAccessRoles = $this->getSchemaEditorAccessRoles();
        if (count($schemaEditorAccessRoles) === 1) {
            return sprintf(
                \__('Users with role: "%s"', 'gato-graphql'),
                $schemaEditorAccessRoles[0]
            );
        }
        return sprintf(
            \__('Users with any role: "%s"', 'gato-graphql'),
            implode('", "', $schemaEditorAccessRoles)
        );
    }

    /**
     * @return string[]
     */
    abstract protected function getSchemaEditorAccessRoles(): array;

    public function canAccessSchemaEditor(WP_User $user): bool
    {
        return array_intersect(
            $this->getSchemaEditorAccessRoles(),
            $user->roles
        ) !== [];
    }
}
