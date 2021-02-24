<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Menus\Menu;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLByPoP\GraphQLServer\Configuration\Request as GraphQLServerRequest;
use GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;

class EndpointHelpers
{

    /**
     * Indicate if we are requesting
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     *
     * @return boolean
     */
    public static function isRequestingAdminGraphQLEndpoint(): bool
    {
        return \is_admin()
            && 'POST' == $_SERVER['REQUEST_METHOD']
            && isset($_GET['page'])
            && $_GET['page'] == Menu::getName()
            && isset($_GET[RequestParams::ACTION])
            && $_GET[RequestParams::ACTION] == RequestParams::ACTION_EXECUTE_QUERY;
    }

    /**
     * GraphQL single endpoint to be used in wp-admin
     *
     * @param boolean $enableLowLevelQueryEditing Enable persisted queries to access schema-type directives
     */
    public static function getAdminGraphQLEndpoint(bool $enableLowLevelQueryEditing = false): string
    {
        $endpoint = \admin_url(sprintf(
            'edit.php?page=%s&%s=%s',
            Menu::getName(),
            RequestParams::ACTION,
            RequestParams::ACTION_EXECUTE_QUERY
        ));
        if ($enableLowLevelQueryEditing) {
            // Add /?edit_schema=1 so the query-type directives are also visible
            $moduleRegistry = ModuleRegistryFacade::getInstance();
            if ($moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING)) {
                $endpoint = \add_query_arg(GraphQLServerRequest::URLPARAM_EDIT_SCHEMA, true, $endpoint);
            }
        }
        // If namespaced, add /?use_namespace=1 to the endpoint
        // if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
        //     $endpoint = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpoint);
        // }
        return $endpoint;
    }
}
