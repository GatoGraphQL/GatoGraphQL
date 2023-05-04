<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\Exception\UserAuthorizationException;
use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;

use function is_user_logged_in;

/**
 * UserAuthorization
 */
class UserAuthorization implements UserAuthorizationInterface
{
    use BasicServiceTrait;

    private ?UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry = null;

    final public function setUserAuthorizationSchemeRegistry(UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry): void
    {
        $this->userAuthorizationSchemeRegistry = $userAuthorizationSchemeRegistry;
    }
    final protected function getUserAuthorizationSchemeRegistry(): UserAuthorizationSchemeRegistryInterface
    {
        /** @var UserAuthorizationSchemeRegistryInterface */
        return $this->userAuthorizationSchemeRegistry ??= $this->instanceManager->getInstance(UserAuthorizationSchemeRegistryInterface::class);
    }

    protected function getUserAuthorizationScheme(): UserAuthorizationSchemeInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($accessScheme = $moduleConfiguration->getEditingAccessScheme()) {
            // If the capability does not exist, catch the exception
            try {
                return $this->getUserAuthorizationSchemeRegistry()->getUserAuthorizationScheme($accessScheme);
            } catch (UserAuthorizationException) {
            }
        }

        // Return the default service
        return $this->getUserAuthorizationSchemeRegistry()->getDefaultUserAuthorizationScheme();
    }

    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=graphql_api, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     */
    public function getSchemaEditorAccessCapability(): string
    {
        return $this->getUserAuthorizationScheme()->getSchemaEditorAccessMinimumRequiredCapability();
    }

    public function canAccessSchemaEditor(): bool
    {
        if (!is_user_logged_in()) {
            return false;
        }
        $userAuthorizationScheme = $this->getUserAuthorizationScheme();
        $user = wp_get_current_user();
        return $userAuthorizationScheme->canAccessSchemaEditor($user);
    }
}
