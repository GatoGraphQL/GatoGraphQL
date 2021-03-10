<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;

/**
 * UserAuthorization
 */
class UserAuthorization implements UserAuthorizationInterface
{
    /**
     * The different ways to grant access to the schema editor
     *
     * @return string[]
     */
    public function getAccessSchemes(): array
    {
        return [
            AccessSchemes::ADMIN_ONLY,
            AccessSchemes::POST,
        ];
    }

    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=graphql_api, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     *
     * @return string
     */
    public function getSchemaEditorAccessCapability(): string
    {
        $accessScheme = ComponentConfiguration::getEditingAccessScheme();
        $accessSchemeCapabilities = [
            AccessSchemes::ADMIN_ONLY => 'manage_options',
            AccessSchemes::POST => 'edit_posts',
        ];
        // If the option chosen does not exist, or none provided, use the "admin" by default
        return $accessSchemeCapabilities[$accessScheme] ?? 'manage_options';
    }

    public function canAccessSchemaEditor(): bool
    {
        return \current_user_can($this->getSchemaEditorAccessCapability());
    }
}
