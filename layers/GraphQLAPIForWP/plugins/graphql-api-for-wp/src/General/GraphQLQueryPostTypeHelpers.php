<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

use GraphQLAPI\GraphQLAPI\General\BlockContentHelpers;
use WP_Post;

class GraphQLQueryPostTypeHelpers
{

    /**
     * A GraphQL Query Custom Post Type is hierarchical: each query post can have a parent,
     * enabling to fetch attributes from the parent post
     * If a GraphiQL block has not defined a query or variables, or the CPT post has not defined
     * its access control list or cache control list,
     * then these attributes are retrieved from the parent, until all attributes have a value.
     *
     * This enables to implement strategies for different GraphQL query hierarchies, for instance:
     *
     * 1. Define root queries called "MobileApp" and "Website", with their corresponding ACL/CCL,
     * and have the actual GraphQL queries inherit from them
     * 2. Define a root GraphQL query without variables, and extend with posts "MobileApp" and "Website"
     * with different variables, eg: changing the value for `$limit`
     *
     * @param \WP_Post $graphQLQueryPost The post to extract the attributes from
     * @param bool $inheritAttributes Indicate if to fetch attributes (query/variables) from ancestor posts
     * @return mixed[] Array with 2 elements: [$graphQLQuery, $graphQLVariables]
     */
    public static function getGraphQLQueryPostAttributes(?WP_Post $graphQLQueryPost, bool $inheritAttributes): array
    {
        /**
         * Obtain the attributes from the block:
         * - Empty query: get it from the first ancestor that defines a query
         * - Variables: combine them all, with descendant's having more priority
         */
        $graphQLQuery = null;
        $graphQLVariables = [];
        while (!is_null($graphQLQueryPost)) {
            /**
             * If the query has a parent, maybe get the query/variables from the parent
             */
            $inheritQuery = false;
            if ($inheritAttributes && $graphQLQueryPost->post_parent) {
                list(
                    $inheritQuery,
                ) = BlockContentHelpers::getSinglePersistedQueryOptionsBlockAttributesFromPost($graphQLQueryPost);
            }
            list(
                $postGraphQLQuery,
                $postGraphQLVariables
            ) = BlockContentHelpers::getSingleGraphiQLBlockAttributesFromPost($graphQLQueryPost);
            // Set the query unless it must be inherited from the parent
            if (is_null($graphQLQuery) && !$inheritQuery) {
                $graphQLQuery = $postGraphQLQuery;
            }
            /**
             * Combine all variables. Variables is saved as a string, convert to array
             * Watch out! If the variables have a wrong format, eg: with an additional trailing comma, such as this:
             * {
             *   "limit": 3,
             * }
             * Then doing `json_decode` will return NULL
             */
            if ($postGraphQLVariables) {
                $postGraphQLVariables = json_decode($postGraphQLVariables, true) ?? [];
                $graphQLVariables = array_merge(
                    $postGraphQLVariables,
                    $graphQLVariables
                );
            }

            // Keep iterating with this posts' ancestors
            if ($inheritQuery) {
                $graphQLQueryPost = \get_post($graphQLQueryPost->post_parent);
                // If it's trashed, then do not use
                if (!is_null($graphQLQueryPost) && $graphQLQueryPost->post_status == 'trash') {
                    $graphQLQueryPost = null;
                }
            } else {
                // Otherwise, finish iterating
                $graphQLQueryPost = null;
            }
        }
        return [
            $graphQLQuery,
            $graphQLVariables,
        ];
    }
}
