<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

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
    protected function init(): void
    {
        \add_filter(
            'application_password_is_api_request',
            $this->isAPIRequest(...),
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
    public function isAPIRequest(bool $isAPIRequest): bool
    {
        if ($isAPIRequest) {
            return $isAPIRequest;
        }

        /**
         * Check if the (slashed) requested URL starts with any
         * of the (slashed) GraphQL endpoints.
         */
        $requestedURLPath = '/' . trim(App::getRequest()->getPathInfo(), '/\\') . '/';
        foreach ($this->getGraphQLEndpointPathPrefixes() as $graphQLEndpointPathPrefix) {
            $graphQLEndpointPathPrefix = '/' . trim($graphQLEndpointPathPrefix, '/\\') . '/';
            if (str_starts_with($requestedURLPath, $graphQLEndpointPathPrefix)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string[]
     */
    protected function getGraphQLEndpointPathPrefixes(): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $userSettingsManager = UserSettingsManagerFacade::getInstance();

        $graphQLEndpointPathPrefixes = [];
        if ($moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT)) {
            $graphQLEndpointPathPrefixes[] = $userSettingsManager->getSetting(
                EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                ModuleSettingOptions::PATH
            );
        }
        if ($moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS)) {
            $graphQLEndpointPathPrefixes[] = $userSettingsManager->getSetting(
                EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                ModuleSettingOptions::PATH
            );
        }
        if ($moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::PERSISTED_QUERIES)) {
            $graphQLEndpointPathPrefixes[] = $userSettingsManager->getSetting(
                EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                ModuleSettingOptions::PATH
            );
        }

        return $graphQLEndpointPathPrefixes;
    }
}
