<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use GraphQLByPoP\GraphQLServer\Configuration\Request as GraphQLServerRequest;

class EndpointHelpers
{
    public function __construct(
        protected PluginMenu $pluginMenu,
        protected ModuleRegistryInterface $moduleRegistry
    ) {
    }

    /**
     * Indicate if we are requesting
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     */
    public function isRequestingAdminConfigurableSchemaGraphQLEndpoint(): bool
    {
        return \is_admin()
            && 'POST' == $_SERVER['REQUEST_METHOD']
            && isset($_GET['page'])
            && $_GET['page'] == $this->pluginMenu->getName()
            && isset($_GET[RequestParams::ACTION])
            && $_GET[RequestParams::ACTION] == RequestParams::ACTION_EXECUTE_QUERY;
    }

    /**
     * Indicate if we are requesting
     * /wp-admin/edit.php?page=graphql_api&action=execute_query&behavior=unrestricted
     */
    public function isRequestingAdminFixedSchemaGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminConfigurableSchemaGraphQLEndpoint()
            && isset($_GET[RequestParams::BEHAVIOR])
            && $_GET[RequestParams::BEHAVIOR] == RequestParams::BEHAVIOR_UNRESTRICTED;
    }

    /**
     * Indicate if we are requesting
     * /wp-admin/edit.php?page=graphql_api&action=execute_query&persisted_query_id=...
     */
    public function isRequestingAdminPersistedQueryGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminConfigurableSchemaGraphQLEndpoint()
            && isset($_GET[RequestParams::PERSISTED_QUERY_ID]);
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
            $this->pluginMenu->getName(),
            RequestParams::ACTION,
            RequestParams::ACTION_EXECUTE_QUERY
        ));
        if ($enableLowLevelQueryEditing) {
            // Add /?edit_schema=1 so the query-type directives are also visible
            if ($this->moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING)) {
                $endpoint = \add_query_arg(GraphQLServerRequest::URLPARAM_EDIT_SCHEMA, true, $endpoint);
            }
        }
        // If namespaced, add /?use_namespace=1 to the endpoint
        // if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
        //     $endpoint = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpoint);
        // }
        return $endpoint;
    }

    /**
     * GraphQL endpoint to be used in the WordPress editor.
     * It has the full schema, including "unrestricted" admin fields.
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
    public function getAdminPersistedQueryGraphQLEndpoint(string | int $persistedQueryCustomPostID, bool $enableLowLevelQueryEditing = false): string
    {
        return \add_query_arg(
            RequestParams::PERSISTED_QUERY_ID,
            $persistedQueryCustomPostID,
            $this->getAdminConfigurableSchemaGraphQLEndpoint($enableLowLevelQueryEditing)
        );
    }

    public function getAdminPersistedQueryCustomPostID(): ?int
    {
        return (int) $_REQUEST[RequestParams::PERSISTED_QUERY_ID] ?? null;
    }
}
