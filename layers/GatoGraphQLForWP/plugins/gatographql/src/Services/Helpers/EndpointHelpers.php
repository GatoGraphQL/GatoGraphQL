<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\Constants\AdminGraphQLEndpointGroups;
use GatoGraphQL\GatoGraphQL\Constants\HookNames;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Services\Menus\PluginMenu;
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

    final protected function getPluginMenu(): PluginMenu
    {
        if ($this->pluginMenu === null) {
            /** @var PluginMenu */
            $pluginMenu = $this->instanceManager->getInstance(PluginMenu::class);
            $this->pluginMenu = $pluginMenu;
        }
        return $this->pluginMenu;
    }

    /**
     * Indicate if we are requesting the wp-admin endpoint, which
     * powers the GraphiQL/Voyager clients, and can also be invoked
     * by 3rd-party plugins and developers to fetch data for their
     * blocks on the WordPress editor, under:
     *
     *   /wp-admin/edit.php?page=gatographql&action=execute_query
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
     *   /wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=pluginOwnUse
     */
    public function isRequestingAdminPluginOwnUseGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminGraphQLEndpoint()
            && App::query(RequestParams::ENDPOINT_GROUP) === AdminGraphQLEndpointGroups::PLUGIN_OWN_USE;
    }

    /**
     * Indicate if we are requesting the internal wp-admin endpoint
     * used on the WordPress editor to power developer's blocks
     * for their own sites, under:
     *
     *   /wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=blockEditor
     */
    public function isRequestingAdminBlockEditorGraphQLEndpoint(): bool
    {
        return $this->isRequestingAdminGraphQLEndpoint()
            && App::query(RequestParams::ENDPOINT_GROUP) === AdminGraphQLEndpointGroups::BLOCK_EDITOR;
    }

    /**
     * Indicate if we are requesting the wp-admin endpoint that
     * fetches data for Persisted Queries, under:
     *
     *   /wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=persistedQuery&persisted_query_id=...
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
     * Indicate if we are requesting a custom admin endpoint
     */
    public function isRequestingCustomAdminGraphQLEndpoint(): bool
    {
        if (!$this->isRequestingAdminGraphQLEndpoint()) {
            return false;
        }

        $endpointGroup = $this->getAdminGraphQLEndpointGroup();
        if ($this->isPredefinedAdminGraphQLEndpointGroup($endpointGroup)) {
            return false;
        }

        return true;
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
     */
    public function getAdminGraphQLEndpoint(
        ?string $endpointGroup = null,
    ): string {
        $endpoint = \admin_url(sprintf(
            'edit.php?page=%s&%s=%s',
            $this->getPluginMenu()->getName(),
            RequestParams::ACTION,
            RequestParams::ACTION_EXECUTE_QUERY
        ));

        if ($endpointGroup !== null) {
            $endpoint = \add_query_arg(
                RequestParams::ENDPOINT_GROUP,
                $endpointGroup,
                $endpoint
            );
        }

        // Add mandatory params from the request, and maybe enable XDebug
        return RequestHelpers::addRequestParamsToEndpoint($endpoint);
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
            $this->getPredefinedAdminGraphQLEndpointGroups()
        );
    }

    /**
     * @return string[]
     */
    protected function getPredefinedAdminGraphQLEndpointGroups(): array
    {
        return [
            AdminGraphQLEndpointGroups::DEFAULT,
            AdminGraphQLEndpointGroups::PERSISTED_QUERY,
            AdminGraphQLEndpointGroups::PLUGIN_OWN_USE,
            AdminGraphQLEndpointGroups::BLOCK_EDITOR,
        ];
    }

    protected function isPredefinedAdminGraphQLEndpointGroup(string $endpointGroup): bool
    {
        return in_array($endpointGroup, $this->getPredefinedAdminGraphQLEndpointGroups());
    }

    /**
     * GraphQL endpoint to be used in the WordPress editor.
     * It has the full schema, including "admin" fields.
     */
    public function getAdminPluginOwnUseGraphQLEndpoint(): string
    {
        return $this->getAdminGraphQLEndpoint(AdminGraphQLEndpointGroups::PLUGIN_OWN_USE);
    }

    /**
     * GraphQL endpoint to be used by developers to feed data
     * to blocks in their own sites.
     */
    public function getAdminBlockEditorGraphQLEndpoint(): string
    {
        return $this->getAdminGraphQLEndpoint(AdminGraphQLEndpointGroups::BLOCK_EDITOR);
    }

    /**
     * GraphQL endpoint to be used in the admin, when editing Persisted Queries
     */
    public function getAdminPersistedQueryGraphQLEndpoint(
        string|int $persistedQueryEndpointID,
    ): string {
        return \add_query_arg(
            [
                RequestParams::PERSISTED_QUERY_ID => $persistedQueryEndpointID,
            ],
            $this->getAdminGraphQLEndpoint(AdminGraphQLEndpointGroups::PERSISTED_QUERY)
        );
    }

    public function getAdminPersistedQueryID(): ?string
    {
        if (App::query(RequestParams::ENDPOINT_GROUP) !== AdminGraphQLEndpointGroups::PERSISTED_QUERY) {
            return null;
        }

        /** @var string|null */
        $persistedQueryEndpointID = App::query(RequestParams::PERSISTED_QUERY_ID);
        if ($persistedQueryEndpointID === null) {
            return null;
        }
        return $persistedQueryEndpointID;
    }
}
