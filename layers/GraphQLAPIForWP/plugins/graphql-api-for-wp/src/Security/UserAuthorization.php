<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\Registries\UserAuthorizationSchemeRegistryFacade;
use InvalidArgumentException;

/**
 * UserAuthorization
 */
class UserAuthorization implements UserAuthorizationInterface
{
    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=graphql_api, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     */
    public function getSchemaEditorAccessCapability(): string
    {
        $accessSchemeCapability = null;
        if ($accessScheme = ComponentConfiguration::getEditingAccessScheme()) {
            $userAuthorizationSchemeRegistry = UserAuthorizationSchemeRegistryFacade::getInstance();
            // If the capability does not exist, fall back on the "admin" one
            try {
                $accessSchemeCapability = $userAuthorizationSchemeRegistry->getSchemaEditorAccessCapability($accessScheme);
            } catch (InvalidArgumentException) {
                
            }
        }

        // Default access is the admin
        if ($accessSchemeCapability === null) {
            return $userAuthorizationSchemeRegistry->getDefaultSchemaEditorAccessCapability();
        }
        // $accessSchemeCapabilities = [
        //     AccessSchemes::ADMIN_ONLY => 'manage_options',
        //     AccessSchemes::POST => 'edit_posts',
        // ];
        // If the option chosen does not exist, or none provided, use the "admin" by default
        return $accessSchemeCapability;
    }

    public function canAccessSchemaEditor(): bool
    {
        return \current_user_can($this->getSchemaEditorAccessCapability());
    }
}
