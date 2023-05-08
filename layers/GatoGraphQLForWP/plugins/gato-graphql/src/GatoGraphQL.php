<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\HttpFoundation\Response;

class GatoGraphQL
{
    private static ?EndpointHelpers $endpointHelpers = null;

    final protected static function getEndpointHelpers(): EndpointHelpers
    {
        if (self::$endpointHelpers === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var EndpointHelpers */
            $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
            self::$endpointHelpers = $endpointHelpers;
        }
        return self::$endpointHelpers;
    }

    /**
     * Obtain the URL of the private wp-admin endpoint
     * (which powers the GraphiQL/Interactive Schema clients):
     *
     *   /wp-admin/edit.php?page=gato_graphql&action=execute_query
     *
     * This endpoint is affected by the configuration selected in the
     * Settings page, for the selected Schema Configuration for the
     * private endpoint, and by the selected default values.
     */
    final public static function getAdminEndpoint(): string
    {
        return self::getEndpointHelpers()->getAdminGraphQLEndpoint();
    }

    /**
     * Obtain the private wp-admin endpoint used by blocks in the
     * WordPress block editor (allowing any application, theme
     * or plugin to use GraphQL to fetch data for their blocks):
     *
     *   /wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=blockEditor
     *
     * This endpoint is NOT affected by the configuration selected in the
     * Settings page; it has a pre-defined non-restrictive configuration,
     * allowing to fetch any piece of data (eg: any CPT, any settings from
     * `wp_options`, any meta entry, etc).
     */
    final public static function getAdminBlockEditorEndpoint(): string
    {
        return self::getEndpointHelpers()->getAdminBlockEditorGraphQLEndpoint();
    }

    /**
     * Obtain a custom private wp-admin endpoint:
     *
     *   /wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group={customEndpointGroup}
     *
     * This custom endpoint must be defined and configured via PHP code,
     * allowing the developer to expose a private endpoint that has a
     * specific configuration, defined via code, to power the application,
     * theme or plugin.
     *
     * See the Recipes section to learn how to define a custom private endpoint.
     */
    final public static function getAdminCustomEndpoint(string $endpointGroup): string
    {
        return self::getEndpointHelpers()->getAdminGraphQLEndpoint($endpointGroup);
    }

    /**
     * Execute a GraphQL query against the internal GraphQL Server.
     *
     * This query execution is affected by the configuration selected in the
     * Settings page, for the selected Schema Configuration for the private
     * endpoint, and/or default Settings values.
     *
     * This situation also applies whenever the query executed against the
     * internal GraphQL server was triggered by another GraphQL query
     * while being resolved in an endpoint with a different configuration (
     * such as the public endpoint "graphql/").
     *
     * For instance: Let's say that we have configured the single endpoint
     * "graphql/" to apply some Access Control Lists, and we execute
     * mutation `createPost` against it:
     *
     *   ```
     *   mutation {
     *     createPost(input: {...}) {
     *       # ...
     *     }
     *   }
     *   ```
     * 
     * Then there is a hook on `wp_insert_post`, that executes some
     * query against the internal GraphQL server (eg: to send a notification
     * to the site admin):
     *
     *   ```
     *   add_action(
     *     "wp_insert_post",
     *     fn (int $post_id) => GatoGraphQL::executeQuery("...", ["postID" => $post_id])
     *   );
     *   ```
     *
     * This GraphQL query be resolved using the configuration applied to
     * the internal GraphQL server, and not to the public endpoint (hence,
     * those Access Control Lists will be applied only if they are also
     * part of that configuration).
     * 
     * @param array<string,mixed> $variables
     * @return Response A Response object containing the response body and headers from resolving the query
     */
    public static function executeQuery(
        string $query,
        array $variables = [],
        ?string $operationName = null
    ): Response {
        /**
         * Keep the current AppThread, switch to the GraphQLServer's
         * one, resolve the query, and then restore the current AppThread.
         */
        $currentAppThread = App::getAppThread();
        App::setAppThread($this->appThread);

        /**
         * Because an "internal" request may be triggered
         * while resolving another "internal" request,
         * backup and then restore the App's state.
         */
        $appStateManager = App::getAppStateManager();
        $appState = $appStateManager->getAppState();

        $response = parent::execute(
            $query,
            $variables,
            $operationName,
        );

        // Restore the App's state
        $appStateManager->setAppState($appState);

        // Restore the original AppThread
        App::setAppThread($currentAppThread);

        return $response;
    }
}
