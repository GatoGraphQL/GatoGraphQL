<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;

/**
 * UserAuthorization
 */
class UserAuthorization
{
    public const ACCESS_SCHEME_ADMIN_ONLY = 'admin';
    public const ACCESS_SCHEME_POST = 'post';

    /**
     * The different ways to grant access to the schema editor
     *
     * @return string[]
     */
    public static function getAccessSchemes(): array
    {
        return [
            self::ACCESS_SCHEME_ADMIN_ONLY,
            self::ACCESS_SCHEME_POST,
        ];
    }

    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=graphql_api, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     *
     * @return string
     */
    public static function getSchemaEditorAccessCapability(): string
    {
        $accessScheme = ComponentConfiguration::getEditingAccessScheme();
        $accessSchemeCapabilities = [
            self::ACCESS_SCHEME_ADMIN_ONLY => 'manage_options',
            self::ACCESS_SCHEME_POST => 'edit_posts',
        ];
        // If the option chosen does not exist, or none provided, use the "admin" by default
        return $accessSchemeCapabilities[$accessScheme] ?? 'manage_options';
    }

    public static function canAccessSchemaEditor(): bool
    {
        return \current_user_can(self::getSchemaEditorAccessCapability());
    }
}
