<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoPAPI\APIEndpoints\EndpointUtils;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Routing\RoutingHelperServiceInterface;

/**
 * Use:
 *
 *   curl -i \
 *     --user "{ USER }:{ APPLICATION PASSWORD}" \
 *     -X POST \
 *     -H "Content-Type: application/json" \
 *     -d '{"query": "{ id me { name } }"}' \
 *     https://mysite.com/graphql/
 */
class ApplicationPasswordAuthorizationHookSet extends AbstractHookSet
{
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?RoutingHelperServiceInterface $routingHelperService = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    final protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }

    final public function setRoutingHelperService(RoutingHelperServiceInterface $routingHelperService): void
    {
        $this->routingHelperService = $routingHelperService;
    }
    final protected function getRoutingHelperService(): RoutingHelperServiceInterface
    {
        if ($this->routingHelperService === null) {
            /** @var RoutingHelperServiceInterface */
            $routingHelperService = $this->instanceManager->getInstance(RoutingHelperServiceInterface::class);
            $this->routingHelperService = $routingHelperService;
        }
        return $this->routingHelperService;
    }

    protected function init(): void
    {
        \add_filter(
            'application_password_is_api_request',
            $this->isGraphQLAPIRequest(...),
            PHP_INT_MAX // Execute last
        );
    }

    /**
     * Check if requesting a GraphQL endpoint.
     *
     * Because the AppStateProviders have not been initialized yet,
     * we can't check ->doingJSON().
     *
     * As a workaround, retrieve the configuration for all GraphQL endpoints
     * (Single endpoint, custom endpoint, and persisted queries) and,
     * if any of them is enabled, check if the URL starts with their
     * path (even if that specific endpoint is disabled).
     */
    public function isGraphQLAPIRequest(bool $isAPIRequest): bool
    {
        if ($isAPIRequest) {
            return $isAPIRequest;
        }

        /**
         * Check if the (slashed) requested URL starts with any
         * of the (slashed) GraphQL endpoints.
         *
         * Use `getRequestURI` as to remove the language info from
         * the URI when in subfolder-based Multisite.
         */
        $requestURI = $this->getRoutingHelperService()->getRequestURI() ?? '';
        $requestURI = EndpointUtils::removeMarkersFromURI($requestURI);
        $requestURI = EndpointUtils::slashURI($requestURI);
        foreach ($this->getGraphQLEndpointPaths() as $graphQLEndpointPath) {
            $graphQLEndpointPath = '/' . trim($graphQLEndpointPath, '/\\') . '/';
            if (str_starts_with($requestURI, $graphQLEndpointPath)) {
                return true;
            }
        }
        return false;
    }

    /**
     * GraphQL endpoint paths (if enabled):
     *
     * - Single endpoint
     * - Custom endpoints
     * - Persisted query endpoints
     *
     * @return string[]
     */
    protected function getGraphQLEndpointPaths(): array
    {
        $moduleRegistry = $this->getModuleRegistry();
        $userSettingsManager = $this->getUserSettingsManager();

        $graphQLEndpointPaths = [];
        if ($moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT)) {
            $graphQLEndpointPaths[] = $userSettingsManager->getSetting(
                EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                ModuleSettingOptions::PATH
            );
        }
        if ($moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS)) {
            $graphQLEndpointPaths[] = $userSettingsManager->getSetting(
                EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                ModuleSettingOptions::PATH
            );
        }
        if ($moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::PERSISTED_QUERIES)) {
            $graphQLEndpointPaths[] = $userSettingsManager->getSetting(
                EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                ModuleSettingOptions::PATH
            );
        }

        return $graphQLEndpointPaths;
    }
}
