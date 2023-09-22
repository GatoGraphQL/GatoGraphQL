<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\Services\BlockAccessors\PersistedQueryEndpointAPIHierarchyBlockAccessor;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Helpers\GraphQLQueryPostTypeHelpers;
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
        if ($this->graphQLQueryPostTypeHelpers === null) {
            /** @var GraphQLQueryPostTypeHelpers */
            $graphQLQueryPostTypeHelpers = $this->instanceManager->getInstance(GraphQLQueryPostTypeHelpers::class);
            $this->graphQLQueryPostTypeHelpers = $graphQLQueryPostTypeHelpers;
        }
        return $this->graphQLQueryPostTypeHelpers;
    }
    final public function setPersistedQueryEndpointAPIHierarchyBlockAccessor(PersistedQueryEndpointAPIHierarchyBlockAccessor $persistedQueryEndpointAPIHierarchyBlockAccessor): void
    {
        $this->persistedQueryEndpointAPIHierarchyBlockAccessor = $persistedQueryEndpointAPIHierarchyBlockAccessor;
    }
    final protected function getPersistedQueryEndpointAPIHierarchyBlockAccessor(): PersistedQueryEndpointAPIHierarchyBlockAccessor
    {
        if ($this->persistedQueryEndpointAPIHierarchyBlockAccessor === null) {
            /** @var PersistedQueryEndpointAPIHierarchyBlockAccessor */
            $persistedQueryEndpointAPIHierarchyBlockAccessor = $this->instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlockAccessor::class);
            $this->persistedQueryEndpointAPIHierarchyBlockAccessor = $persistedQueryEndpointAPIHierarchyBlockAccessor;
        }
        return $this->persistedQueryEndpointAPIHierarchyBlockAccessor;
    }
    final public function setPersistedQueryEndpointGraphiQLBlock(PersistedQueryEndpointGraphiQLBlock $persistedQueryEndpointGraphiQLBlock): void
    {
        $this->persistedQueryEndpointGraphiQLBlock = $persistedQueryEndpointGraphiQLBlock;
    }
    final protected function getPersistedQueryEndpointGraphiQLBlock(): PersistedQueryEndpointGraphiQLBlock
    {
        if ($this->persistedQueryEndpointGraphiQLBlock === null) {
            /** @var PersistedQueryEndpointGraphiQLBlock */
            $persistedQueryEndpointGraphiQLBlock = $this->instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
            $this->persistedQueryEndpointGraphiQLBlock = $persistedQueryEndpointGraphiQLBlock;
        }
        return $this->persistedQueryEndpointGraphiQLBlock;
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
                    $graphQLQueryPostAttributesEntry = $this->getGraphQLQueryPostTypeHelpers()->getGraphQLQueryPostAttributes($graphQLQueryPost, true);

                    // Render the block again, using the inherited attributes
                    $inheritedGraphQLBlockAttributes = [
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $graphQLQueryPostAttributesEntry->query,
                        // To render the variables in the block, they must be json_encoded
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $graphQLQueryPostAttributesEntry->variables !== []
                            ? (string)\wp_json_encode($graphQLQueryPostAttributesEntry->variables)
                            : []
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
