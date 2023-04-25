<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Constants\AdminGraphQLEndpointGroups;
use GraphQLAPI\GraphQLAPI\Constants\HookNames;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use GraphQLByPoP\GraphQLServer\Constants\Params as GraphQLServerParams;
use PoP\ComponentModel\Configuration\RequestHelpers;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;

use function apply_filters;

class EndpointHelpers
{
    use BasicServiceTrait;

    /** @var string[]|null */
    private ?array $supportedAdminGraphQLEndpointGroups = null;

    private ?PluginMenu $pluginMenu = null;

    final public function setPluginMenu(PluginMenu $pluginMenu): void
    {
        $this->pluginMenu = $pluginMenu;
    }
    final protected function getPluginMenu(): PluginMenu
    {
        /** @var PluginMenu */
        return $this->pluginMenu ??= $this->instanceManager->getInstance(PluginMenu::class);
    }

    /**
     * Indicate if we are requesting the wp-admin endpoint, which
     * powers the GraphiQL/Voyager clients, and can also be invoked
     * by 3rd-party plugins and developers to fetch data for their
     * blocks on the WordPress editor, under:
     *
     *   /wp-admin/edit.php?page=graphql_api&action=execute_query
     */
    public function isRequestingAdminGraphQLEndpoint(): bool
    {
        return \is_admin()
            && 'POST' === App::server('REQUEST_METHOD')
            && App::query('page') === $this->getPluginMenu()->getName()
            && App::query(RequestParams::ACTION) === RequestParams::ACTION_EXECUTE_QUERY;
    }

    /**
     * Indicate if we are requesting the internal wp-admin endpoint
     * used on the WordPress editor to power this plugin's blocks
     * (for the different CPTs: SchemaConfig, ACLs, CCLs, etc), under:
     *
     *   /wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=pluginOwnUse
     */
    public function isRequestingAdminPluginOwnUseGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminGraphQLEndpoint()
            && App::query(RequestParams::ENDPOINT_GROUP) === AdminGraphQLEndpointGroups::PLUGIN_OWN_USE;
    }

    /**
     * Indicate if we are requesting the wp-admin endpoint that
     * fetches data for Persisted Queries, under:
     *
     *   /wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=persistedQuery&persisted_query_id=...
     */
    public function isRequestingAdminPersistedQueryGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminGraphQLEndpoint()
            && App::query(RequestParams::ENDPOINT_GROUP) === AdminGraphQLEndpointGroups::PERSISTED_QUERY;
            // && App::getRequest()->query->has(RequestParams::PERSISTED_QUERY_ID);
    }

    /**
     * Indicate if we are requesting the default admin endpoint
     */
    public function isRequestingDefaultAdminGraphQLEndpoint(): bool
    {
        if (!$this->isRequestingAdminGraphQLEndpoint()) {
            return false;
        }
        return $this->getAdminGraphQLEndpointGroup() === AdminGraphQLEndpointGroups::DEFAULT;
    }

    /**
     * Indicate if we are requesting any admin endpoint
     * except persisted queries.
     */
    public function isRequestingNonPersistedQueryAdminGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminGraphQLEndpoint()
            && !$this->isRequestingAdminPersistedQueryGraphQLEndpoint();
    }

    /**
     * GraphQL single endpoint to be used in wp-admin
     *
     * @param boolean $enableLowLevelQueryEditing Enable persisted queries to access schema-type directives
     */
    public function getAdminGraphQLEndpoint(bool $enableLowLevelQueryEditing = false): string
    {
        $endpoint = \admin_url(sprintf(
            'edit.php?page=%s&%s=%s',
            $this->getPluginMenu()->getName(),
            RequestParams::ACTION,
            RequestParams::ACTION_EXECUTE_QUERY
        ));
        if ($enableLowLevelQueryEditing) {
            // Add /?edit_schema=1 so the query-type directives are also visible
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->enableLowLevelPersistedQueryEditing()) {
                $endpoint = \add_query_arg(GraphQLServerParams::EDIT_SCHEMA, true, $endpoint);
            }
        }

        // Add mandatory params from the request, and maybe enable XDebug
        $endpoint = RequestHelpers::addRequestParamsToEndpoint($endpoint);

        // If namespaced, add /?use_namespace=1 to the endpoint
        // /** @var ComponentModelModuleConfiguration */
        // $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        // if ($moduleConfiguration->mustNamespaceTypes()) {
        //     $endpoint = \add_query_arg(APIParams::USE_NAMESPACE, true, $endpoint);
        // }
        return $endpoint;
    }

    /**
     * Admin GraphQL endpoint group. If not provided,
     * the (empty) string represents the default group,
     * used by the private GraphiQL client.
     */
    public function getAdminGraphQLEndpointGroup(): string
    {
        /** @var string */
        $endpointGroup = App::query(RequestParams::ENDPOINT_GROUP, AdminGraphQLEndpointGroups::DEFAULT);

        /**
         * If the endpointGroup is not supported, use the
         * default one.
         */
        if (!in_array($endpointGroup, $this->getSupportedAdminGraphQLEndpointGroups())) {
            return AdminGraphQLEndpointGroups::DEFAULT;
        }
        return $endpointGroup;
    }

    /**
     * @return string[]
     */
    public function getSupportedAdminGraphQLEndpointGroups(): array
    {
        if ($this->supportedAdminGraphQLEndpointGroups === null) {
            $this->supportedAdminGraphQLEndpointGroups = $this->doGetSupportedAdminGraphQLEndpointGroups();
        }
        return $this->supportedAdminGraphQLEndpointGroups;
    }

    /**
     * @return string[]
     */
    private function doGetSupportedAdminGraphQLEndpointGroups(): array
    {
        $supportedAdminEndpointGroups =  apply_filters(
            HookNames::SUPPORTED_ADMIN_ENDPOINT_GROUPS,
            []
        );
        // Mandatory groups, add them after the filter
        return array_merge(
            $supportedAdminEndpointGroups,
            [
                AdminGraphQLEndpointGroups::DEFAULT,
                AdminGraphQLEndpointGroups::PLUGIN_OWN_USE,
                AdminGraphQLEndpointGroups::PERSISTED_QUERY,
            ]
        );
    }

    /**
     * GraphQL endpoint to be used in the WordPress editor.
     * It has the full schema, including "admin" fields.
     */
    public function getAdminPluginOwnUseGraphQLEndpoint(): string
    {
        return \add_query_arg(
            RequestParams::ENDPOINT_GROUP,
            AdminGraphQLEndpointGroups::PLUGIN_OWN_USE,
            $this->getAdminGraphQLEndpoint()
        );
    }

    /**
     * GraphQL endpoint to be used in the admin, when editing Persisted Queries
     *
     * @param boolean $enableLowLevelQueryEditing Enable persisted queries to access schema-type directives
     */
    public function getAdminPersistedQueryGraphQLEndpoint(
        string|int $persistedQueryEndpointCustomPostID,
        bool $enableLowLevelQueryEditing = false,
    ): string {
        return \add_query_arg(
            [
                RequestParams::ENDPOINT_GROUP => AdminGraphQLEndpointGroups::PERSISTED_QUERY,
                RequestParams::PERSISTED_QUERY_ID => $persistedQueryEndpointCustomPostID,
            ],
            $this->getAdminGraphQLEndpoint($enableLowLevelQueryEditing)
        );
    }

    public function getAdminPersistedQueryCustomPostID(): ?int
    {
        if (App::query(RequestParams::ENDPOINT_GROUP) !== AdminGraphQLEndpointGroups::PERSISTED_QUERY) {
            return null;
        }

        /** @var string|null */
        $persistedQueryID = App::query(RequestParams::PERSISTED_QUERY_ID);
        if ($persistedQueryID === null) {
            return null;
        }
        return (int) $persistedQueryID;
    }
}
