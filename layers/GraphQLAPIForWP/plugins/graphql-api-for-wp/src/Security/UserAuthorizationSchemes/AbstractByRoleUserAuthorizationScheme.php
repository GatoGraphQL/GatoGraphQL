<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

use WP_User;

abstract class AbstractByRoleUserAuthorizationScheme extends AbstractUserAuthorizationScheme
{
    public function getName(): string
    {
        return sprintf(
            'access_scheme-role-%s',
            $this->getSchemaEditorAccessRole()
        );
    }

    abstract protected function getSchemaEditorAccessRole(): string;

    public function canAccessSchemaEditor(WP_User $user): bool
    {
        return in_array($this->getSchemaEditorAccessRole(), $user->roles);
    }
}
