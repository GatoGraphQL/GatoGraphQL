<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Hooks;

use PoP\Hooks\AbstractHookSet;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\SchemaServices\FieldResolvers\CPTFieldResolver;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLCacheControlListPostType;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLAccessControlListPostType;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLSchemaConfigurationPostType;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLFieldDeprecationListPostType;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class QueryHooks extends AbstractHookSet
{
    public const NON_EXISTING_ID = "non-existing";

    protected function init()
    {
        $this->hooksAPI->addAction(
            'CMSAPI:customposts:query',
            [$this, 'convertCustomPostsQuery'],
            10,
            2
        );
    }

    /**
     * Remove querying private CPTs
     *
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function convertCustomPostsQuery(array $query, array $options): array
    {
        // Hooks must be active only when resolving the query into IDs,
        // and not when resolving IDs into object, since there we don't have `$options`
        if (
            isset($query['post_type'])
            && (!isset($options[CPTFieldResolver::QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS]) || !$options[CPTFieldResolver::QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS])
            && isset($options['return-type']) && $options['return-type'] == ReturnTypes::IDS
        ) {
            // These CPTs must not be queried from outside, since they contain private configuration data
            $query['post_type'] = array_diff(
                $query['post_type'],
                [
                    GraphQLAccessControlListPostType::POST_TYPE,
                    GraphQLCacheControlListPostType::POST_TYPE,
                    GraphQLFieldDeprecationListPostType::POST_TYPE,
                    GraphQLSchemaConfigurationPostType::POST_TYPE,
                ]
            );
            // If there are no valid postTypes, then return no results
            // By not adding the post type, WordPress will fetch a "post"
            // Then, include a non-existing ID
            if (!$query['post_type']) {
                $query['include'] = self::NON_EXISTING_ID;
            }
        }
        return $query;
    }
}
