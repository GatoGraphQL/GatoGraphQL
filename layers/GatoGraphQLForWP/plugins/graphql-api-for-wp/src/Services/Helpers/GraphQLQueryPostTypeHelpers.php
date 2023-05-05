<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\Services\BlockAccessors\PersistedQueryEndpointAPIHierarchyBlockAccessor;
use GatoGraphQL\GatoGraphQL\Services\BlockAccessors\PersistedQueryEndpointGraphiQLBlockAccessor;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

class GraphQLQueryPostTypeHelpers
{
    use BasicServiceTrait;

    private ?PersistedQueryEndpointGraphiQLBlockAccessor $persistedQueryEndpointGraphiQLBlockAccessor = null;
    private ?PersistedQueryEndpointAPIHierarchyBlockAccessor $persistedQueryEndpointAPIHierarchyBlockAccessor = null;

    final public function setPersistedQueryEndpointGraphiQLBlockAccessor(PersistedQueryEndpointGraphiQLBlockAccessor $persistedQueryEndpointGraphiQLBlockAccessor): void
    {
        $this->persistedQueryEndpointGraphiQLBlockAccessor = $persistedQueryEndpointGraphiQLBlockAccessor;
    }
    final protected function getPersistedQueryEndpointGraphiQLBlockAccessor(): PersistedQueryEndpointGraphiQLBlockAccessor
    {
        /** @var PersistedQueryEndpointGraphiQLBlockAccessor */
        return $this->persistedQueryEndpointGraphiQLBlockAccessor ??= $this->instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlockAccessor::class);
    }
    final public function setPersistedQueryEndpointAPIHierarchyBlockAccessor(PersistedQueryEndpointAPIHierarchyBlockAccessor $persistedQueryEndpointAPIHierarchyBlockAccessor): void
    {
        $this->persistedQueryEndpointAPIHierarchyBlockAccessor = $persistedQueryEndpointAPIHierarchyBlockAccessor;
    }
    final protected function getPersistedQueryEndpointAPIHierarchyBlockAccessor(): PersistedQueryEndpointAPIHierarchyBlockAccessor
    {
        /** @var PersistedQueryEndpointAPIHierarchyBlockAccessor */
        return $this->persistedQueryEndpointAPIHierarchyBlockAccessor ??= $this->instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlockAccessor::class);
    }

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
     * @param WP_Post|null $graphQLQueryPost The post to extract the attributes from
     * @param bool $inheritAttributes Indicate if to fetch attributes (query/variables) from ancestor posts
     * @return array{0:string,1:array<string,mixed>} Array of 2 elements: [query, variables]
     */
    public function getGraphQLQueryPostAttributes(?WP_Post $graphQLQueryPost, bool $inheritAttributes): array
    {
        /**
         * Obtain the attributes from the block:
         * - Empty query: get it from the first ancestor that defines a query
         * - Variables: combine them all, with descendant's having more priority
         */
        $graphQLQuery = '';
        $graphQLVariables = [];
        while ($graphQLQueryPost !== null) {
            /**
             * If the query has a parent, maybe get the query/variables from the parent
             */
            $inheritQuery = false;
            if ($inheritAttributes && $graphQLQueryPost->post_parent) {
                $persistedQueryEndpointAPIHierarchyBlockAttributes = $this->getPersistedQueryEndpointAPIHierarchyBlockAccessor()->getAttributes($graphQLQueryPost);
                if ($persistedQueryEndpointAPIHierarchyBlockAttributes !== null) {
                    $inheritQuery = $persistedQueryEndpointAPIHierarchyBlockAttributes->isInheritQuery();
                }
            }
            $graphiQLBlockAttributes = $this->getPersistedQueryEndpointGraphiQLBlockAccessor()->getAttributes($graphQLQueryPost);
            if ($graphiQLBlockAttributes === null) {
                break;
            }
            // Set the query unless it must be inherited from the parent
            if (empty($graphQLQuery) && !$inheritQuery) {
                $graphQLQuery = $graphiQLBlockAttributes->getQuery();
            }
            /**
             * Combine all variables.
             */
            $graphQLVariables = array_merge(
                $graphiQLBlockAttributes->getVariables(),
                $graphQLVariables
            );

            // Keep iterating with this posts' ancestors
            if ($inheritQuery) {
                $graphQLQueryPost = \get_post($graphQLQueryPost->post_parent);
                // If it's trashed, then do not use
                if ($graphQLQueryPost !== null && $graphQLQueryPost->post_status === 'trash') {
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
