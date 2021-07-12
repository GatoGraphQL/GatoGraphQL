<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistryInterface;
use InvalidArgumentException;

/**
 * UserAuthorization
 */
class UserAuthorization implements UserAuthorizationInterface
{
    public function __construct(
        protected UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry
    ) {
    }
    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=graphql_api, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     */
    public function getSchemaEditorAccessCapability(): string
    {
        $accessSchemeCapability = null;
        if ($accessScheme = ComponentConfiguration::getEditingAccessScheme()) {
            // If the capability does not exist, catch the exception
            try {
                $accessSchemeCapability = $this->userAuthorizationSchemeRegistry->getUserAuthorizationScheme($accessScheme)->getSchemaEditorAccessCapability();
            } catch (InvalidArgumentException) {
            }
        }

        // Return the default access
        if ($accessSchemeCapability === null) {
            // This function also throws an exception. Let it bubble up - that's an application error
            $defaultUserAuthorizationScheme = $this->userAuthorizationSchemeRegistry->getDefaultUserAuthorizationScheme();
            return $defaultUserAuthorizationScheme->getSchemaEditorAccessCapability();
        }
        return $accessSchemeCapability;
    }

    public function canAccessSchemaEditor(): bool
    {
        return \current_user_can($this->getSchemaEditorAccessCapability());
    }
}
