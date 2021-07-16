<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\BlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLQueryExecutionCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockContentHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLQueryTaxonomy;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet as GraphQLRequestVarsHooks;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\HooksAPIInterface;
use WP_Post;

class GraphQLPersistedQueryEndpointCustomPostType extends AbstractGraphQLQueryExecutionCustomPostType
{
    use WithBlockRegistryCustomPostTypeTrait;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        HooksAPIInterface $hooksAPI,
        protected BlockContentHelpers $blockContentHelpers,
        protected GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers,
        protected PersistedQueryEndpointBlockRegistryInterface $persistedQueryBlockRegistry
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $userAuthorization,
            $hooksAPI,
        );
    }

    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'graphql-query';
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 2;
    }

    /**
     * Access endpoints under /graphql-query, or wherever it is configured to
     */
    protected function getSlugBase(): ?string
    {
        return ComponentConfiguration::getPersistedQuerySlugBase();
    }

    /**
     * Custom post type name
     */
    public function getCustomPostTypeName(): string
    {
        return \__('GraphQL persisted query', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL persisted queries', 'graphql-api');
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute query', 'graphql-api');
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array<string, string>
     */
    protected function getCustomPostTypeLabels(string $name_uc, string $names_uc, string $names_lc): array
    {
        /**
         * Because the name is too long, shorten it for the admin menu only
         */
        return array_merge(
            parent::getCustomPostTypeLabels($name_uc, $names_uc, $names_lc),
            array(
                'all_items' => \__('Persisted Queries', 'graphql-api'),
            )
        );
    }

    /**
     * The Query is publicly accessible, and the permalink must be configurable
     */
    protected function isPublic(): bool
    {
        return true;
    }

    /**
     * Taxonomies
     *
     * @return string[]
     */
    protected function getTaxonomies(): array
    {
        return [
            GraphQLQueryTaxonomy::TAXONOMY_CATEGORY,
        ];
    }

    /**
     * Hierarchical
     */
    protected function isHierarchical(): bool
    {
        return true;
    }

    // /**
    //  * Show in admin bar
    //  */
    // protected function showInAdminBar(): bool
    // {
    //     return true;
    // }

    protected function getBlockRegistry(): BlockRegistryInterface
    {
        return $this->persistedQueryBlockRegistry;
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
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
            /**
             * @var PersistedQueryEndpointGraphiQLBlock
             */
            $graphiQLBlock = $this->instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

            // Check if the user is authorized to see the content
            $ancestorContent = null;
            if ($this->userAuthorization->canAccessSchemaEditor()) {
                /**
                 * If the query has a parent, also render the inherited output
                 */
                list(
                    $inheritQuery
                ) = $this->blockContentHelpers->getSinglePersistedQueryOptionsBlockAttributesFromPost($graphQLQueryPost);
                if ($inheritQuery) {
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
                    $ancestorContent = $graphiQLBlock->renderBlock($inheritedGraphQLBlockAttributes, '');
                }
            } else {
                $ancestorContent = $graphiQLBlock->renderUnauthorizedAccess();
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

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array with 2 elements: [$graphQLQuery, $graphQLVariables]
     */
    protected function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the post (or from its parents), and set it in $vars
         */
        return $this->graphQLQueryPostTypeHelpers->getGraphQLQueryPostAttributes($graphQLQueryPost, true);
    }

    protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock
    {
        /**
         * @var PersistedQueryEndpointOptionsBlock
         */
        $block = $this->instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        return $block;
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     */
    protected function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        if ($customPost === null) {
            return parent::doURLParamsOverrideGraphQLVariables($customPost);
        }
        $default = true;
        $optionsBlockDataItem = $this->getOptionsBlockDataItem($customPost);
        if ($optionsBlockDataItem === null) {
            return $default;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][PersistedQueryEndpointOptionsBlock::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? $default;
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @param array<array> $vars_in_array
     */
    public function addGraphQLVars(array $vars_in_array): void
    {
        if (\is_singular($this->getCustomPostType())) {
            // Check if it is enabled, by configuration
            [&$vars] = $vars_in_array;
            if (!$this->isEnabled($vars['routing-state']['queried-object-id'])) {
                return;
            }

            /** @var GraphQLRequestVarsHooks */
            $graphQLAPIRequestHookSet = $this->instanceManager->getInstance(GraphQLRequestVarsHooks::class);

            // The Persisted Query is also standard GraphQL
            $graphQLAPIRequestHookSet->setStandardGraphQLVars($vars);

            // Remove the VarsHookSet from the GraphQLRequest, so it doesn't process the GraphQL query
            // Otherwise it will add error "The query in the body is empty"
            /**
             * @var callable
             */
            $action = [$graphQLAPIRequestHookSet, 'addVars'];
            \remove_action(
                'ApplicationState:addVars',
                $action,
                20
            );

            // Execute the original logic
            parent::addGraphQLVars($vars_in_array);
        }
    }
}
