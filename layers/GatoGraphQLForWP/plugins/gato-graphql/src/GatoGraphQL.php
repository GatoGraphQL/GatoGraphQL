<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Exception\FileCorruptedException;
use GatoGraphQL\GatoGraphQL\Exception\FileNotFoundException;
use GatoGraphQL\GatoGraphQL\Exception\GraphQLServerNotReadyException;
use GatoGraphQL\GatoGraphQL\Exception\PersistedQueryNotFoundException;
use GatoGraphQL\GatoGraphQL\Server\InternalGraphQLServerFactory;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\GraphQLQueryPostTypeHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\HttpFoundation\Response;
use WP_Post;

use function get_page_by_path;
use function get_post;

class GatoGraphQL
{
    private static ?EndpointHelpers $endpointHelpers = null;
    private static ?GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers = null;
    private static ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;

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
    final protected static function getGraphQLQueryPostTypeHelpers(): GraphQLQueryPostTypeHelpers
    {
        if (self::$graphQLQueryPostTypeHelpers === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var GraphQLQueryPostTypeHelpers */
            $graphQLQueryPostTypeHelpers = $instanceManager->getInstance(GraphQLQueryPostTypeHelpers::class);
            self::$graphQLQueryPostTypeHelpers = $graphQLQueryPostTypeHelpers;
        }
        return self::$graphQLQueryPostTypeHelpers;
    }
    final protected static function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        if (self::$graphQLPersistedQueryEndpointCustomPostType === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var GraphQLPersistedQueryEndpointCustomPostType */
            $graphQLPersistedQueryEndpointCustomPostType = $instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
            self::$graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
        }
        return self::$graphQLPersistedQueryEndpointCustomPostType;
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
     * The response is a Response object. To obtain the response content,
     * execute:
     *
     * ```php
     * $query = "{ ... }";
     * $response = GatoGraphQL::executeQuery($query);
     * $responseContent = json_decode($response->getContent(), false);
     * $responseData = $responseContent->data;
     * $responseErrors = $responseContent->errors;
     * ```
     *
     * The Response object also contains any produced header (eg: if some Cache
     * Control List was applied, it would add the "Cache-Control" header):
     *
     * ```php
     * $responseHeaders = $response->getHeaders();
     * $responseCacheControlHeader = $response->getHeaderLine('Cache-Control');
     * ```
     *
     * --------------------------------------------------------------------
     *
     * This query execution is affected by the configuration selected in the
     * Settings page, for the selected Schema Configuration for the private
     * endpoint, and by the selected default values.
     *
     * Please notice: This situation also applies whenever the query executed
     * against the internal GraphQL server was triggered by some other GraphQL
     * query while being resolved in an endpoint with a different configuration
     * (such as the public endpoint "graphql/").
     *
     * For instance: Let's say that we have configured the single endpoint
     * "graphql/" to apply an Access Control List to validate users by IP,
     * and we execute mutation `createPost` against this endpoint:
     *
     *   ```
     *   mutation {
     *     createPost(input: {...}) {
     *       # ...
     *     }
     *   }
     *   ```
     *
     * Then only visitors from that IP will be able to execute this mutation.
     *
     * Then there is a hook on `wp_insert_post` that executes some
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
     * This GraphQL query will be resolved using the configuration applied to
     * the internal GraphQL server, and not to the public endpoint.
     *
     * As a result, the validation by user IP will not take place
     * (that is, unless that Access Control List was also applied
     * to the internal GraphQL server).
     *
     * @param array<string,mixed> $variables
     * @return Response A Response object containing the response body and headers from resolving the query
     * @throws GraphQLServerNotReadyException If the GraphQL Server is not ready yet
     */
    public static function executeQuery(
        string $query,
        array $variables = [],
        ?string $operationName = null
    ): Response {
        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        return $graphQLServer->execute(
            $query,
            $variables,
            $operationName,
        );
    }

    /**
     * Execute the GraphQL query contained in a file against the
     * internal GraphQL Server.
     *
     * The response is a Response object. To obtain the response content,
     * execute:
     *
     * ```php
     * $file = "/path/to/file/myQuery.gql";
     * $response = GatoGraphQL::executeQueryInFile($file);
     * $responseContent = json_decode($response->getContent(), false);
     * $responseData = $responseContent->data;
     * $responseErrors = $responseContent->errors;
     * ```
     *
     * The Response object also contains any produced header (eg: if some Cache
     * Control List was applied, it would add the "Cache-Control" header):
     *
     * ```php
     * $responseHeaders = $response->getHeaders();
     * $responseCacheControlHeader = $response->getHeaderLine('Cache-Control');
     * ```
     *
     * --------------------------------------------------------------------
     *
     * This query execution is affected by the configuration selected in the
     * Settings page, for the selected Schema Configuration for the private
     * endpoint, and by the selected default values.
     *
     * Please notice: This situation also applies whenever the query executed
     * against the internal GraphQL server was triggered by some other GraphQL
     * query while being resolved in an endpoint with a different configuration
     * (such as the public endpoint "graphql/").
     *
     * For instance: Let's say that we have configured the single endpoint
     * "graphql/" to apply an Access Control List to validate users by IP,
     * and we execute mutation `createPost` against this endpoint:
     *
     *   ```
     *   mutation {
     *     createPost(input: {...}) {
     *       # ...
     *     }
     *   }
     *   ```
     *
     * Then only visitors from that IP will be able to execute this mutation.
     *
     * Then there is a hook on `wp_insert_post` that executes some
     * query against the internal GraphQL server (eg: to send a notification
     * to the site admin):
     *
     *   ```
     *   add_action(
     *     "wp_insert_post",
     *     fn (int $post_id) => GatoGraphQL::executeQueryInFile("...", ["postID" => $post_id])
     *   );
     *   ```
     *
     * This GraphQL query will be resolved using the configuration applied to
     * the internal GraphQL server, and not to the public endpoint.
     *
     * As a result, the validation by user IP will not take place
     * (that is, unless that Access Control List was also applied
     * to the internal GraphQL server).
     *
     * @param array<string,mixed> $variables
     * @return Response A Response object containing the response body and headers from resolving the query
     * @throws GraphQLServerNotReadyException If the GraphQL Server is not ready yet
     * @throws FileNotFoundException If the file does not exist
     * @throws FileCorruptedException If the file cannot be read
     */
    public static function executeQueryInFile(
        string $file,
        array $variables = [],
        ?string $operationName = null
    ): Response {
        if (!file_exists($file)) {
            throw new FileNotFoundException(
                sprintf('GraphQL query file \'%s\' does not exist', $file)
            );
        }

        $query = file_get_contents($file);
        if ($query === false) {
            throw new FileCorruptedException(
                sprintf('Loading GraphQL query file \'%s\' failed', $file)
            );
        }

        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        return $graphQLServer->execute(
            $query,
            $variables,
            $operationName,
        );
    }

    /**
     * Execute the persisted query against the internal GraphQL Server.
     *
     * The response is a Response object. To obtain the response content,
     * execute:
     *
     * ```php
     * $persistedQueryID = 33;
     * $response = GatoGraphQL::executePersistedQuery($persistedQueryID);
     * $responseContent = json_decode($response->getContent(), false);
     * $responseData = $responseContent->data;
     * $responseErrors = $responseContent->errors;
     * ```
     *
     * The Response object also contains any produced header (eg: if some Cache
     * Control List was applied, it would add the "Cache-Control" header):
     *
     * ```php
     * $responseHeaders = $response->getHeaders();
     * $responseCacheControlHeader = $response->getHeaderLine('Cache-Control');
     * ```
     *
     * --------------------------------------------------------------------
     *
     * This query execution is affected by the configuration selected in the
     * Settings page, for the selected Schema Configuration for the private
     * endpoint, and by the selected default values.
     *
     * Please notice: This situation also applies whenever the query executed
     * against the internal GraphQL server was triggered by some other GraphQL
     * query while being resolved in an endpoint with a different configuration
     * (such as the public endpoint "graphql/").
     *
     * For instance: Let's say that we have configured the single endpoint
     * "graphql/" to apply an Access Control List to validate users by IP,
     * and we execute mutation `createPost` against this endpoint:
     *
     *   ```
     *   mutation {
     *     createPost(input: {...}) {
     *       # ...
     *     }
     *   }
     *   ```
     *
     * Then only visitors from that IP will be able to execute this mutation.
     *
     * Then there is a hook on `wp_insert_post` that executes some
     * query against the internal GraphQL server (eg: to send a notification
     * to the site admin):
     *
     *   ```
     *   add_action(
     *     "wp_insert_post",
     *     fn (int $post_id) => GatoGraphQL::executePersistedQuery(33, ["postID" => $post_id])
     *   );
     *   ```
     *
     * This GraphQL query will be resolved using the configuration applied to
     * the internal GraphQL server, and not to the public endpoint.
     *
     * As a result, the validation by user IP will not take place
     * (that is, unless that Access Control List was also applied
     * to the internal GraphQL server).
     *
     * @param string|int $persistedQueryIDOrSlug The parameter is considered to be the persisted query ID if it is an integer, or the slug if it is a string
     * @param array<string,mixed> $variables Variable values to override over those stored in the persisted query entry
     * @return Response A Response object containing the response body and headers from resolving the query
     * @throws GraphQLServerNotReadyException If the GraphQL Server is not ready yet
     * @throws PersistedQueryNotFoundException If the persisted query does not exist
     */
    public static function executePersistedQuery(
        string|int $persistedQueryIDOrSlug,
        array $variables = [],
        ?string $operationName = null
    ): Response {
        $isPersistedQueryID = is_integer($persistedQueryIDOrSlug);

        /** @var WP_Post|null */
        $graphQLQueryPost = $isPersistedQueryID
            ? get_post($persistedQueryIDOrSlug)
            : get_page_by_path(
                $persistedQueryIDOrSlug,
                OBJECT,
                self::getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType()
            );

        if ($graphQLQueryPost === null) {
            throw new PersistedQueryNotFoundException(
                $isPersistedQueryID
                    ? sprintf('Persisted query with ID \'%s\' does not exist', $persistedQueryIDOrSlug)
                    : sprintf('Persisted query with slug \'%s\' does not exist', $persistedQueryIDOrSlug)
            );
        }

        /**
         * Extract the query and variables from the persisted query post
         */
        $graphQLQueryPostAttributesEntry = self::getGraphQLQueryPostTypeHelpers()->getGraphQLQueryPostAttributes($graphQLQueryPost, true);

        /**
         * Merge the variables in the document with the provided ones
         */
        $variables = array_merge(
            $graphQLQueryPostAttributesEntry->variables,
            $variables
        );

        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        return $graphQLServer->execute(
            $graphQLQueryPostAttributesEntry->query,
            $variables,
            $operationName,
        );
    }
}
