<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\AbstractEndpointResolver;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet as GraphQLRequestVarsHookSet;
use PoP\EngineWP\Templates\TemplateHelpers;
use WP_Post;

class AdminEndpointResolver extends AbstractEndpointResolver implements AdminEndpointExecuterServiceTagInterface
{
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?QueryRetrieverInterface $queryRetriever = null;
    private ?GraphQLRequestVarsHookSet $graphQLRequestVarsHookSet = null;

    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    final public function setQueryRetriever(QueryRetrieverInterface $queryRetriever): void
    {
        $this->queryRetriever = $queryRetriever;
    }
    final protected function getQueryRetriever(): QueryRetrieverInterface
    {
        return $this->queryRetriever ??= $this->instanceManager->getInstance(QueryRetrieverInterface::class);
    }
    final public function setGraphQLRequestVarsHookSet(GraphQLRequestVarsHookSet $graphQLRequestVarsHookSet): void
    {
        $this->graphQLRequestVarsHookSet = $graphQLRequestVarsHookSet;
    }
    final protected function getGraphQLRequestVarsHookSet(): GraphQLRequestVarsHookSet
    {
        return $this->graphQLRequestVarsHookSet ??= $this->instanceManager->getInstance(GraphQLRequestVarsHookSet::class);
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array of 2 elements: [query, variables]
     */
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        return $this->getQueryRetriever()->extractRequestedGraphQLQueryPayload();
    }

    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        return false;
    }

    /**
     * Execute the GraphQL query when posting to:
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     */
    public function isEndpointBeingRequested(): bool
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return false;
        }
        return $this->getEndpointHelpers()->isRequestingAdminConfigurableSchemaGraphQLEndpoint();
    }

    public function executeEndpoint(): void
    {
        /**
         * Print the global JS variables, required by the blocks
         */
        $this->printGlobalVariables();

        $this->printTemplateInAdminAndExit();
    }

    /**
     * Print JS variables which are used by several blocks,
     * before the blocks are loaded
     */
    protected function printGlobalVariables(): void
    {
        \add_action('admin_print_scripts', function (): void {
            // Make sure the user has access to the editor
            if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
                $scriptTag = '<script type="text/javascript">var %s = "%s"</script>';
                /**
                 * The endpoint against which to execute GraphQL queries on the admin.
                 * This GraphQL schema is modified by user preferences:
                 * - Disabled types/directives are not in the schema
                 * - Nested mutations enabled or not
                 * - Schema namespaced or not
                 * - etc
                 */
                \printf(
                    $scriptTag,
                    'GRAPHQL_API_ADMIN_CONFIGURABLESCHEMA_ENDPOINT',
                    $this->getEndpointHelpers()->getAdminConfigurableSchemaGraphQLEndpoint()
                );
                /**
                 * The endpoint against which to execute GraphQL queries on the WordPress editor,
                 * for Gutenberg blocks which require some field that must necessarily be enabled.
                 * This GraphQL schema is not modified by user preferences:
                 * - All types/directives are always in the schema
                 * - The "admin" fields are in the schema
                 * - Nested mutations enabled, without removing the redundant fields in the Root
                 * - No namespacing
                 */
                \printf(
                    $scriptTag,
                    'GRAPHQL_API_ADMIN_FIXEDSCHEMA_ENDPOINT',
                    $this->getEndpointHelpers()->getAdminFixedSchemaGraphQLEndpoint()
                );
            }
        });
    }

    /**
     * To print the JSON output, we use WordPress templates,
     * which are used only in the front-end.
     * When in the admin, we must manually load the template,
     * and then exit
     */
    protected function printTemplateInAdminAndExit(): void
    {
        \add_action(
            'admin_init',
            function (): void {
                // Make sure the user has access to the editor
                if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
                    include TemplateHelpers::getTemplateFile();
                    die;
                }
            }
        );
    }
}
