<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\BlockAccessors\PersistedQueryEndpointAPIHierarchyBlockAccessor;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use WP_Post;

abstract class AbstractViewPersistedQueryEndpointSourceEndpointExecuter extends AbstractViewSourceEndpointExecuter
{
    private ?GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers = null;
    private ?PersistedQueryEndpointAPIHierarchyBlockAccessor $persistedQueryEndpointAPIHierarchyBlockAccessor = null;
    private ?PersistedQueryEndpointGraphiQLBlock $persistedQueryEndpointGraphiQLBlock = null;

    final public function setGraphQLQueryPostTypeHelpers(GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers): void
    {
        $this->graphQLQueryPostTypeHelpers = $graphQLQueryPostTypeHelpers;
    }
    final protected function getGraphQLQueryPostTypeHelpers(): GraphQLQueryPostTypeHelpers
    {
        /** @var GraphQLQueryPostTypeHelpers */
        return $this->graphQLQueryPostTypeHelpers ??= $this->instanceManager->getInstance(GraphQLQueryPostTypeHelpers::class);
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
    final public function setPersistedQueryEndpointGraphiQLBlock(PersistedQueryEndpointGraphiQLBlock $persistedQueryEndpointGraphiQLBlock): void
    {
        $this->persistedQueryEndpointGraphiQLBlock = $persistedQueryEndpointGraphiQLBlock;
    }
    final protected function getPersistedQueryEndpointGraphiQLBlock(): PersistedQueryEndpointGraphiQLBlock
    {
        /** @var PersistedQueryEndpointGraphiQLBlock */
        return $this->persistedQueryEndpointGraphiQLBlock ??= $this->instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
    }

    /**
     * Add the parent query to the rendering of the GraphQL Query CPT
     */
    protected function getGraphQLQuerySourceContent(string $content, WP_Post $graphQLQueryPost): ?string
    {
        $content = parent::getGraphQLQuerySourceContent($content, $graphQLQueryPost);

        if ($content === null) {
            return null;
        }

        /**
         * If the GraphQL query has a parent, possibly it is missing the query/variables/acl/ccl attributes,
         * which inherits from some parent
         * In that case, render the block twice:
         * 1. The current block, with missing attributes
         * 2. The final block, completing the missing attributes from its parent
         */
        if ($graphQLQueryPost->post_parent) {
            // Check if the user is authorized to see the content
            $ancestorContent = null;
            if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
                /**
                 * If the query has a parent, also render the inherited output
                 */
                $persistedQueryEndpointAPIHierarchyBlockAttributes = $this->getPersistedQueryEndpointAPIHierarchyBlockAccessor()->getAttributes($graphQLQueryPost);
                if (
                    $persistedQueryEndpointAPIHierarchyBlockAttributes !== null
                    && $persistedQueryEndpointAPIHierarchyBlockAttributes->isInheritQuery()
                ) {
                    // Fetch the attributes using inheritance
                    list(
                        $inheritedGraphQLQuery,
                        $inheritedGraphQLVariables
                    ) = $this->getGraphQLQueryPostTypeHelpers()->getGraphQLQueryPostAttributes($graphQLQueryPost, true);
                    // To render the variables in the block, they must be json_encoded
                    if ($inheritedGraphQLVariables) {
                        $inheritedGraphQLVariables = (string)json_encode($inheritedGraphQLVariables);
                    }
                    // Render the block again, using the inherited attributes
                    $inheritedGraphQLBlockAttributes = [
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $inheritedGraphQLQuery,
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $inheritedGraphQLVariables,
                    ];
                    // Add the new rendering to the output, and a description for each
                    $ancestorContent = $this->getPersistedQueryEndpointGraphiQLBlock()->renderBlock($inheritedGraphQLBlockAttributes, '');
                }
            } else {
                $ancestorContent = $this->getRenderingHelpers()->getUnauthorizedAccessHTMLMessage();
            }
            if ($ancestorContent !== null) {
                $content = sprintf(
                    '%s%s<hr/>%s%s',
                    \__('<p><u>GraphQL query, inheriting properties from ancestor(s): </u></p>'),
                    $ancestorContent,
                    \__('<p><u>GraphQL query, as defined in this level: </u></p>'),
                    $content
                );
            }
        }

        return $content;
    }
}
