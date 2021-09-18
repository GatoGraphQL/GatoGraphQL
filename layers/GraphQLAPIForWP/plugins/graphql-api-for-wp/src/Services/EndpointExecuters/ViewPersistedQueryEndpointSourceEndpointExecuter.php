<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockAccessors\PersistedQueryEndpointAPIHierarchyBlockAccessor;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use WP_Post;

class ViewPersistedQueryEndpointSourceEndpointExecuter extends AbstractViewSourceEndpointExecuter implements PersistedQueryEndpointExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType,
        protected UserAuthorizationInterface $userAuthorization,
        protected GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers,
        protected PersistedQueryEndpointAPIHierarchyBlockAccessor $persistedQueryEndpointAPIHierarchyBlockAccessor,
        protected PersistedQueryEndpointGraphiQLBlock $persistedQueryEndpointGraphiQLBlock,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->graphQLPersistedQueryEndpointCustomPostType;
    }

    /**
     * Add the parent query to the rendering of the GraphQL Query CPT
     */
    protected function getGraphQLQuerySourceContent(string $content, WP_Post $graphQLQueryPost): string
    {
        $content = parent::getGraphQLQuerySourceContent($content, $graphQLQueryPost);

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
            if ($this->userAuthorization->canAccessSchemaEditor()) {
                /**
                 * If the query has a parent, also render the inherited output
                 */
                $persistedQueryEndpointAPIHierarchyBlockAttributes = $this->persistedQueryEndpointAPIHierarchyBlockAccessor->getAttributes($graphQLQueryPost);
                if (
                    $persistedQueryEndpointAPIHierarchyBlockAttributes !== null
                    && $persistedQueryEndpointAPIHierarchyBlockAttributes->isInheritQuery()
                ) {
                    // Fetch the attributes using inheritance
                    list(
                        $inheritedGraphQLQuery,
                        $inheritedGraphQLVariables
                    ) = $this->graphQLQueryPostTypeHelpers->getGraphQLQueryPostAttributes($graphQLQueryPost, true);
                    // To render the variables in the block, they must be json_encoded
                    if ($inheritedGraphQLVariables) {
                        $inheritedGraphQLVariables = json_encode($inheritedGraphQLVariables);
                    }
                    // Render the block again, using the inherited attributes
                    $inheritedGraphQLBlockAttributes = [
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $inheritedGraphQLQuery,
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $inheritedGraphQLVariables,
                    ];
                    // Add the new rendering to the output, and a description for each
                    $ancestorContent = $this->persistedQueryEndpointGraphiQLBlock->renderBlock($inheritedGraphQLBlockAttributes, '');
                }
            } else {
                $ancestorContent = $this->persistedQueryEndpointGraphiQLBlock->renderUnauthorizedAccess();
            }
            if (!is_null($ancestorContent)) {
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
