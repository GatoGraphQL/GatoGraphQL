<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockContentHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use GraphQLAPI\GraphQLAPI\Services\Menus\Menu;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLQueryExecutionCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLQueryTaxonomy;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHooks as GraphQLRequestVarsHooks;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use WP_Post;

class GraphQLPersistedQueryCustomPostType extends AbstractGraphQLQueryExecutionCustomPostType
{
    function __construct(
        Menu $menu,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        protected BlockContentHelpers $blockContentHelpers,
        protected GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers
    ) {
        parent::__construct(
            $menu,
            $moduleRegistry,
            $userAuthorization
        );
    }

    /**
     * Custom Post Type name
     */
    public const CUSTOM_POST_TYPE = 'graphql-query';

    /**
     * Custom Post Type name
     */
    protected function getCustomPostType(): string
    {
        return self::CUSTOM_POST_TYPE;
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
    public function getPostTypeName(): string
    {
        return \__('GraphQL persisted query', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getPostTypePluralNames(bool $uppercase): string
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
    protected function getPostTypeLabels(string $name_uc, string $names_uc, string $names_lc): array
    {
        /**
         * Because the name is too long, shorten it for the admin menu only
         */
        return array_merge(
            parent::getPostTypeLabels($name_uc, $names_uc, $names_lc),
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
    //  *
    //  * @return bool
    //  */
    // protected function showInAdminBar(): bool
    // {
    //     return true;
    // }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array<array> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        $template = parent::getGutenbergTemplate();

        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var PersistedQueryGraphiQLBlock
         */
        $graphiQLBlock = $instanceManager->getInstance(PersistedQueryGraphiQLBlock::class);
        /**
         * Add before the SchemaConfiguration block
         */
        array_unshift($template, [$graphiQLBlock->getBlockFullName()]);

        /**
         * @var PersistedQueryOptionsBlock
         */
        $persistedQueryOptionsBlock = $instanceManager->getInstance(PersistedQueryOptionsBlock::class);
        $template[] = [$persistedQueryOptionsBlock->getBlockFullName()];
        return $template;
    }

    /**
     * Indicates if to lock the Gutenberg templates
     */
    protected function lockGutenbergTemplate(): bool
    {
        return true;
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
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var PersistedQueryGraphiQLBlock
             */
            $graphiQLBlock = $instanceManager->getInstance(PersistedQueryGraphiQLBlock::class);

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
                        PersistedQueryGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $inheritedGraphQLQuery,
                        PersistedQueryGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $inheritedGraphQLVariables,
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
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var PersistedQueryOptionsBlock
         */
        $block = $instanceManager->getInstance(PersistedQueryOptionsBlock::class);
        return $block;
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     */
    protected function doURLParamsOverrideGraphQLVariables(WP_Post|int $postOrID): bool
    {
        $default = true;
        $optionsBlockDataItem = $this->getOptionsBlockDataItem($postOrID);
        if (is_null($optionsBlockDataItem)) {
            return $default;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][PersistedQueryOptionsBlock::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? $default;
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

            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var GraphQLRequestVarsHooks */
            $graphQLAPIRequestHookSet = $instanceManager->getInstance(GraphQLRequestVarsHooks::class);

            // The Persisted Query is also standard GraphQL
            $graphQLAPIRequestHookSet->setStandardGraphQLVars($vars);

            // Remove the VarsHooks from the GraphQLRequest, so it doesn't process the GraphQL query
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
