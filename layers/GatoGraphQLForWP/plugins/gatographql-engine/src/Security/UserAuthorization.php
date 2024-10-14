<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Exception\UserAuthorizationException;
use GatoGraphQL\GatoGraphQL\Registries\UserAuthorizationSchemeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;

use function is_user_logged_in;
use function is_multisite;
use function is_super_admin;

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
        if ($this->userAuthorizationSchemeRegistry === null) {
            /** @var UserAuthorizationSchemeRegistryInterface */
            $userAuthorizationSchemeRegistry = $this->instanceManager->getInstance(UserAuthorizationSchemeRegistryInterface::class);
            $this->userAuthorizationSchemeRegistry = $userAuthorizationSchemeRegistry;
        }
        return $this->userAuthorizationSchemeRegistry;
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
     * against the admin endpoint /wp-admin/?page=gatographql, and execute queries against it).
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

        /**
         * The multisite super admin has ID=1 and no roles!!!
         * Hence, to allow super admins to access the services,
         * grant explicit access.
         */
        if (is_multisite() && is_super_admin()) {
            return true;
        }

        $userAuthorizationScheme = $this->getUserAuthorizationScheme();
        $user = wp_get_current_user();
        return $userAuthorizationScheme->canAccessSchemaEditor($user);
    }
}
