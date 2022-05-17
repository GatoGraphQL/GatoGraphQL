<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\Exception\UserAuthorizationException;
use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistryInterface;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;

use function current_user_can;

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
        return $this->userAuthorizationSchemeRegistry ??= $this->instanceManager->getInstance(UserAuthorizationSchemeRegistryInterface::class);
    }
    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=graphql_api, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     */
    public function getSchemaEditorAccessCapability(): string
    {
        $accessSchemeCapability = null;
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        if ($accessScheme = $moduleConfiguration->getEditingAccessScheme()) {
            // If the capability does not exist, catch the exception
            try {
                $accessSchemeCapability = $this->getUserAuthorizationSchemeRegistry()->getUserAuthorizationScheme($accessScheme)->getSchemaEditorAccessCapability();
            } catch (UserAuthorizationException) {
            }
        }

        // Return the default access
        if ($accessSchemeCapability === null) {
            // This function also throws an exception. Let it bubble up - that's an application error
            $defaultUserAuthorizationScheme = $this->getUserAuthorizationSchemeRegistry()->getDefaultUserAuthorizationScheme();
            return $defaultUserAuthorizationScheme->getSchemaEditorAccessCapability();
        }
        return $accessSchemeCapability;
    }

    public function canAccessSchemaEditor(): bool
    {
        return current_user_can($this->getSchemaEditorAccessCapability());
    }
}
