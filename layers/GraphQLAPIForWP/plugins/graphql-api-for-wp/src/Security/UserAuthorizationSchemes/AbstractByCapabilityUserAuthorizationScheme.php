<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

use WP_User;

abstract class AbstractByCapabilityUserAuthorizationScheme extends AbstractUserAuthorizationScheme
{
    public function getName(): string
    {
        return sprintf(
            'access_scheme-capability-%s',
            $this->getSchemaEditorAccessCapability()
        );
    }

    public function getDescription(): string
    {
        return sprintf(
            \__('Users with capability: "%s"', 'graphql-api'),
            $this->getSchemaEditorAccessCapability()
        );
    }

    abstract protected function getSchemaEditorAccessCapability(): string;

    public function canAccessSchemaEditor(WP_User $user): bool
    {
        return user_can($user, $this->getSchemaEditorAccessCapability());
    }

    final public function getSchemaEditorAccessMinimumRequiredCapability(): string
    {
        return $this->getSchemaEditorAccessCapability();
    }
}
