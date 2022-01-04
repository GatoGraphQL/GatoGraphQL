<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\AbstractEndpointResolver;
use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\EndpointResolverTrait;
use GraphQLByPoP\GraphQLRequest\Component as GraphQLRequestComponent;
use GraphQLByPoP\GraphQLRequest\ComponentConfiguration as GraphQLRequestComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet as GraphQLRequestVarsHookSet;
use PoP\EngineWP\Templates\TemplateHelpers;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use WP_Post;

class AdminEndpointResolver extends AbstractEndpointResolver
{
    use EndpointResolverTrait {
        EndpointResolverTrait::executeGraphQLQuery as upstreamExecuteGraphQLQuery;
    }

    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?QueryRetrieverInterface $queryRetriever = null;
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;
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
    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
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
     * Do not load the query if already loaded
     * in `processURLParamVars` from `graphql-request/src/Hooks/VarsHookSet.php`
     */
    protected function loadGraphQLQueryAndVariables(): bool
    {
        return !GraphQLRequestComponentConfiguration::disableGraphQLAPIForPoP();
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array of 2 elements: [query, variables]
     */
    protected function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        return $this->getQueryRetriever()->extractRequestedGraphQLQueryPayload();
    }

    /**
     * Execute the GraphQL query when posting to:
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return $this->getEndpointHelpers()->isRequestingAdminConfigurableSchemaGraphQLEndpoint();
    }

    /**
     * Initialize the resolver
     */
    public function initialize(): void
    {
        parent::initialize();

        /**
         * Print the global JS variables, required by the blocks
         */
        $this->printGlobalVariables();

        /**
         * If executing the GraphQL query, resolve it
         */
        if ($this->isGraphQLQueryExecution()) {
            $this->executeGraphQLQuery();
            $this->printTemplateInAdminAndExit();
        }
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
     * Execute the GraphQL query
     */
    protected function executeGraphQLQuery(): void
    {
        /**
         * Only in "init" we can execute `wp_get_current_user`, to validate that the
         * user can execute the query
         */
        \add_action(
            'init',
            function (): void {
                // Make sure the user has access to the editor
                if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
                    $this->upstreamExecuteGraphQLQuery();
                }
            }
        );
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
