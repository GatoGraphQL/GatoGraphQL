<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\PostTypes;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\Blocks\EndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Clients\CustomEndpointGraphiQLClient;
use GraphQLAPI\GraphQLAPI\Clients\CustomEndpointVoyagerClient;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\AbstractGraphQLQueryExecutionPostType;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLQueryTaxonomy;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use GraphQLByPoP\GraphQLRequest\Execution\QueryExecutionHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use WP_Post;

class GraphQLEndpointPostType extends AbstractGraphQLQueryExecutionPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-endpoint';

    /**
     * Custom Post Type name
     */
    protected function getPostType(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Module that enables this PostType
     */
    protected function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 1;
    }

    /**
     * Access endpoints under /graphql, or wherever it is configured to
     */
    protected function getSlugBase(): ?string
    {
        return ComponentConfiguration::getCustomEndpointSlugBase();
    }

    /**
     * Custom post type name
     */
    public function getPostTypeName(): string
    {
        return \__('GraphQL endpoint', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL endpoints', 'graphql-api');
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
                'all_items' => \__('Custom Endpoints', 'graphql-api'),
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
         * @var EndpointOptionsBlock
         */
        $endpointOptionsBlock = $instanceManager->getInstance(EndpointOptionsBlock::class);
        $template[] = [$endpointOptionsBlock->getBlockFullName()];
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
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('View endpoint', 'graphql-api');
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array of 2 elements: [query, variables]
     */
    protected function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        return QueryExecutionHelpers::extractRequestedGraphQLQueryPayload();
    }

    protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var EndpointOptionsBlock
         */
        $block = $instanceManager->getInstance(EndpointOptionsBlock::class);
        return $block;
    }

    /**
     * Indicates if we executing the GraphQL query (`true`) or visualizing the query source (`false`)
     * It returns always `true`, unless passing ?view=source in the single post URL
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return !in_array(
            $_REQUEST[RequestParams::VIEW] ?? null,
            [
                RequestParams::VIEW_GRAPHIQL,
                RequestParams::VIEW_SCHEMA,
                RequestParams::VIEW_SOURCE,
            ]
        );
    }

    /**
     * Set the hook to expose the GraphiQL/Voyager clients
     */
    protected function doSomethingElse(): void
    {
        if (($_REQUEST[RequestParams::VIEW] ?? null) == RequestParams::VIEW_SOURCE) {
            parent::doSomethingElse();
        } else {
            /**
             * Execute at the very last, because Component::boot is executed also on hook "wp",
             * and there is useNamespacing set
             */
            \add_action(
                'wp',
                [$this, 'maybePrintClient'],
                PHP_INT_MAX
            );
        }
    }
    /**
     * Expose the GraphiQL/Voyager clients
     */
    public function maybePrintClient(): void
    {
        $vars = ApplicationState::getVars();
        $customPost = $vars['routing-state']['queried-object'];
        // Make sure there is a post (eg: it has not been deleted)
        if ($customPost === null) {
            return;
        }
        $view = $_REQUEST[RequestParams::VIEW] ?? '';
        // Read from the configuration if to expose the GraphiQL/Voyager client
        if (
            (
                $view == RequestParams::VIEW_GRAPHIQL
                && $this->isGraphiQLEnabled($customPost)
            )
            || (
                $view == RequestParams::VIEW_SCHEMA
                && $this->isVoyagerEnabled($customPost)
            )
        ) {
            // Print the HTML directly from the client
            $clientClasses = [
                RequestParams::VIEW_GRAPHIQL => CustomEndpointGraphiQLClient::class,
                RequestParams::VIEW_SCHEMA => CustomEndpointVoyagerClient::class,
            ];
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var AbstractClient
             */
            $client = $instanceManager->getInstance($clientClasses[$view]);
            echo $client->getClientHTML();
            die;
        }
    }

    /**
     * Read the options block and check the value of attribute "isGraphiQLEnabled"
     *
     * @param WP_Post|int $postOrID
     */
    protected function isGraphiQLEnabled($postOrID): bool
    {
        // Check if disabled by module
        if (!$this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS)) {
            return false;
        }

        // If the endpoint is disabled, then also disable this client
        if (!$this->isEnabled($postOrID)) {
            return false;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            EndpointOptionsBlock::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED,
            true
        );
    }

    /**
     * Read the options block and check the value of attribute "isVoyagerEnabled"
     *
     * @param WP_Post|int $postOrID
     */
    protected function isVoyagerEnabled($postOrID): bool
    {
        // Check if disabled by module
        if (!$this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS)) {
            return false;
        }

        // If the endpoint is disabled, then also disable this client
        if (!$this->isEnabled($postOrID)) {
            return false;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            EndpointOptionsBlock::ATTRIBUTE_NAME_IS_VOYAGER_ENABLED,
            true
        );
    }

    /**
     * Get actions to add for this CPT
     *
     * @param WP_Post $post
     * @return array<string, string>
     */
    protected function getPostTypeTableActions($post): array
    {
        $actions = parent::getPostTypeTableActions($post);

        /**
         * If neither GraphiQL or Voyager are enabled, then already return
         */
        $isGraphiQLEnabled = $this->isGraphiQLEnabled($post);
        $isVoyagerEnabled = $this->isVoyagerEnabled($post);
        if (!$isGraphiQLEnabled && !$isVoyagerEnabled) {
            return $actions;
        }

        $title = \_draft_or_post_title();
        $permalink = \get_permalink($post->ID);
        /**
         * Attach the GraphiQL/Voyager clients
         */
        return array_merge(
            $actions,
            // If GraphiQL enabled, add the "GraphiQL" action
            $isGraphiQLEnabled ? [
                'graphiql' => sprintf(
                    '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                    \add_query_arg(
                        RequestParams::VIEW,
                        RequestParams::VIEW_GRAPHIQL,
                        $permalink
                    ),
                    /* translators: %s: Post title. */
                    \esc_attr(\sprintf(\__('GraphiQL &#8220;%s&#8221;'), $title)),
                    __('GraphiQL', 'graphql-api')
                ),
            ] : [],
            // If Voyager enabled, add the "Schema" action
            $isVoyagerEnabled ? [
                'schema' => sprintf(
                    '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                    \add_query_arg(
                        RequestParams::VIEW,
                        RequestParams::VIEW_SCHEMA,
                        $permalink
                    ),
                    /* translators: %s: Post title. */
                    \esc_attr(\sprintf(\__('Schema &#8220;%s&#8221;'), $title)),
                    __('Interactive schema', 'graphql-api')
                )
            ] : []
        );
    }
}
