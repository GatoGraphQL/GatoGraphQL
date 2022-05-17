<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use GraphQLByPoP\GraphQLServer\Constants\Params as GraphQLServerParams;
use PoP\ComponentModel\Configuration\RequestHelpers;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;

class EndpointHelpers
{
    use BasicServiceTrait;

    private ?PluginMenu $pluginMenu = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setPluginMenu(PluginMenu $pluginMenu): void
    {
        $this->pluginMenu = $pluginMenu;
    }
    final protected function getPluginMenu(): PluginMenu
    {
        return $this->pluginMenu ??= $this->instanceManager->getInstance(PluginMenu::class);
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    /**
     * Indicate if we are requesting
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     */
    public function isRequestingAdminConfigurableSchemaGraphQLEndpoint(): bool
    {
        return \is_admin()
            && 'POST' === App::server('REQUEST_METHOD')
            && App::query('page') === $this->getPluginMenu()->getName()
            && App::query(RequestParams::ACTION) === RequestParams::ACTION_EXECUTE_QUERY;
    }

    /**
     * Indicate if we are requesting
     * /wp-admin/edit.php?page=graphql_api&action=execute_query&behavior=unrestricted
     */
    public function isRequestingAdminFixedSchemaGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminConfigurableSchemaGraphQLEndpoint()
            && App::query(RequestParams::BEHAVIOR) === RequestParams::BEHAVIOR_UNRESTRICTED;
    }

    /**
     * Indicate if we are requesting
     * /wp-admin/edit.php?page=graphql_api&action=execute_query&persisted_query_id=...
     */
    public function isRequestingAdminPersistedQueryGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminConfigurableSchemaGraphQLEndpoint()
            && App::getRequest()->query->has(RequestParams::PERSISTED_QUERY_ID);
    }

    /**
     * Indicate if we are requesting in the wp-admin:
     * Only GraphiQL and Voyager clients
     */
    public function isRequestingGraphQLEndpointForAdminClientOnly(): bool
    {
        return $this->isRequestingAdminConfigurableSchemaGraphQLEndpoint()
            && !$this->isRequestingAdminFixedSchemaGraphQLEndpoint()
            && !$this->isRequestingAdminPersistedQueryGraphQLEndpoint();
    }

    /**
     * Indicate if we are requesting in the wp-admin:
     * GraphiQL and Voyager clients + ACL/CCL configurations
     */
    public function isRequestingGraphQLEndpointForAdminClientOrConfiguration(): bool
    {
        return $this->isRequestingAdminConfigurableSchemaGraphQLEndpoint()
            && !$this->isRequestingAdminPersistedQueryGraphQLEndpoint();
    }

    /**
     * GraphQL single endpoint to be used in wp-admin
     *
     * @param boolean $enableLowLevelQueryEditing Enable persisted queries to access schema-type directives
     */
    public function getAdminConfigurableSchemaGraphQLEndpoint(bool $enableLowLevelQueryEditing = false): string
    {
        $endpoint = \admin_url(sprintf(
            'edit.php?page=%s&%s=%s',
            $this->getPluginMenu()->getName(),
            RequestParams::ACTION,
            RequestParams::ACTION_EXECUTE_QUERY
        ));
        if ($enableLowLevelQueryEditing) {
            // Add /?edit_schema=1 so the query-type directives are also visible
            if ($this->getModuleRegistry()->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING)) {
                $endpoint = \add_query_arg(GraphQLServerParams::EDIT_SCHEMA, true, $endpoint);
            }
        }

        // Maybe enable XDebug
        $endpoint = RequestHelpers::maybeAddParamToDebugRequest($endpoint);

        // If namespaced, add /?use_namespace=1 to the endpoint
        // /** @var ComponentModelModuleConfiguration */
        // $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        // if ($moduleConfiguration->mustNamespaceTypes()) {
        //     $endpoint = \add_query_arg(APIParams::USE_NAMESPACE, true, $endpoint);
        // }
        return $endpoint;
    }

    /**
     * GraphQL endpoint to be used in the WordPress editor.
     * It has the full schema, including "admin" fields.
     */
    public function getAdminFixedSchemaGraphQLEndpoint(): string
    {
        return \add_query_arg(
            RequestParams::BEHAVIOR,
            RequestParams::BEHAVIOR_UNRESTRICTED,
            $this->getAdminConfigurableSchemaGraphQLEndpoint()
        );
    }

    /**
     * GraphQL endpoint to be used in the admin, when editing Persisted Queries
     *
     * @param boolean $enableLowLevelQueryEditing Enable persisted queries to access schema-type directives
     */
    public function getAdminPersistedQueryGraphQLEndpoint(string | int $persistedQueryEndpointCustomPostID, bool $enableLowLevelQueryEditing = false): string
    {
        return \add_query_arg(
            RequestParams::PERSISTED_QUERY_ID,
            $persistedQueryEndpointCustomPostID,
            $this->getAdminConfigurableSchemaGraphQLEndpoint($enableLowLevelQueryEditing)
        );
    }

    public function getAdminPersistedQueryCustomPostID(): ?int
    {
        if ($persistedQueryID = App::query(RequestParams::PERSISTED_QUERY_ID)) {
            return (int) $persistedQueryID;
        }
        return null;
    }
}
