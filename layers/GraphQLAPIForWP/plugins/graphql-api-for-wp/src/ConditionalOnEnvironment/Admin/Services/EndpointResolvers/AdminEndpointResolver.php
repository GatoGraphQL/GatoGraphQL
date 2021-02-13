<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\EndpointResolvers;

use PoP\EngineWP\Templates\TemplateHelpers;
use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;
use GraphQLByPoP\GraphQLRequest\Execution\QueryExecutionHelpers;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\EndpointResolvers\EndpointResolverTrait;
use GraphQLAPI\GraphQLAPI\EndpointResolvers\AbstractEndpointResolver;
use WP_Post;

class AdminEndpointResolver extends AbstractEndpointResolver
{
    use EndpointResolverTrait {
        EndpointResolverTrait::executeGraphQLQuery as upstreamExecuteGraphQLQuery;
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
        return QueryExecutionHelpers::extractRequestedGraphQLQueryPayload();
    }

    /**
     * Execute the GraphQL query when posting to:
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     *
     * @return boolean
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return EndpointHelpers::isRequestingAdminGraphQLEndpoint();
    }

    /**
     * Initialize the resolver
     *
     * @return void
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
     *
     * @return void
     */
    protected function printGlobalVariables(): void
    {
        \add_action('admin_print_scripts', function () {
            // Make sure the user has access to the editor
            if (UserAuthorization::canAccessSchemaEditor()) {
                /**
                 * The endpoint against which to execute GraphQL queries while on the WordPress admin
                 */
                \printf(
                    '<script type="text/javascript">var GRAPHQL_API_ADMIN_ENDPOINT = "%s"</script>',
                    EndpointHelpers::getAdminGraphQLEndpoint()
                );
            }
        });
    }

    /**
     * Execute the GraphQL query
     *
     * @return void
     */
    protected function executeGraphQLQuery(): void
    {
        /**
         * Only in "init" we can execute `wp_get_current_user`, to validate that the
         * user can execute the query
         */
        \add_action(
            'init',
            function () {
                // Make sure the user has access to the editor
                if (UserAuthorization::canAccessSchemaEditor()) {
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
     *
     * @return void
     */
    protected function printTemplateInAdminAndExit(): void
    {
        \add_action(
            'admin_init',
            function () {
                // Make sure the user has access to the editor
                if (UserAuthorization::canAccessSchemaEditor()) {
                    include TemplateHelpers::getTemplateFile();
                    die;
                }
            }
        );
    }
}
