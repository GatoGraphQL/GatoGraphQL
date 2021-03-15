<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use WP_Post;
use PoP\ComponentModel\State\ApplicationState;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigurationBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\EndpointResolverTrait;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use WP_Query;

abstract class AbstractGraphQLQueryExecutionCustomPostType extends AbstractCustomPostType
{
    use EndpointResolverTrait {
        EndpointResolverTrait::getNature as getUpstreamNature;
        EndpointResolverTrait::addGraphQLVars as upstreamAddGraphQLVars;
    }

    /**
     * Indicates if we executing the GraphQL query (`true`) or visualizing the query source (`false`)
     * It returns always `true`, unless passing ?view=source in the single post URL
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return ($_REQUEST[RequestParams::VIEW] ?? null) != RequestParams::VIEW_SOURCE;
    }

    /**
     * Whenever this CPT is saved/updated, the timestamp must be regenerated,
     * because it contains the SchemaConfiguration block, which contains
     * Field Deprecation Lists, which can change the schema
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return true;
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute', 'graphql-api');
    }

    /**
     * Get actions to add for this CPT
     * "View" action must be attached ?view=source, and the view link is called "Execute"
     *
     * @param WP_Post $post
     * @return array<string, string>
     */
    protected function getPostTypeTableActions($post): array
    {
        $actions = parent::getPostTypeTableActions($post);
        $post_type_object = \get_post_type_object($post->post_type);

        /**
         * Code copied from function `handle_row_actions` in file
         * wp-admin/includes/class-wp-posts-list-table.php
         */
        if (!is_null($post_type_object) && \is_post_type_viewable($post_type_object)) {
            $title = \_draft_or_post_title();
            $isEnabled = $this->isEnabled($post);
            $executeLabel = $this->getExecuteActionLabel();
            if (in_array($post->post_status, array('pending', 'draft', 'future'))) {
                $can_edit_post = \current_user_can('edit_post', $post->ID);
                if ($can_edit_post) {
                    $preview_link = \get_preview_post_link($post);
                    if (!is_null($preview_link)) {
                        $actions['view'] = sprintf(
                            '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                            esc_url(\add_query_arg(
                                RequestParams::VIEW,
                                RequestParams::VIEW_SOURCE,
                                $preview_link
                            )),
                            /* translators: %s: Post title. */
                            esc_attr(sprintf(__('Preview source &#8220;%s&#8221;', 'graphql-api'), $title)),
                            __('Preview source', 'graphql-api')
                        );
                        if ($isEnabled) {
                            $actions['execute'] = sprintf(
                                '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                                esc_url($preview_link),
                                esc_attr(sprintf(__('%s &#8220;%s&#8221;', 'graphql-api'), $executeLabel, $title)),
                                $executeLabel
                            );
                        }
                    }
                }
            } elseif ('trash' != $post->post_status) {
                if ($permalink = \get_permalink($post->ID)) {
                    $actions['view'] = sprintf(
                        '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                        \add_query_arg(
                            RequestParams::VIEW,
                            RequestParams::VIEW_SOURCE,
                            $permalink
                        ),
                        esc_attr(sprintf(__('View source &#8220;%s&#8221;', 'graphql-api'), $title)),
                        __('View source', 'graphql-api')
                    );
                    if ($isEnabled) {
                        $actions['execute'] = sprintf(
                            '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                            $permalink,
                            esc_attr(sprintf(__('%s &#8220;%s&#8221;', 'graphql-api'), $executeLabel, $title)),
                            $executeLabel
                        );
                    }
                }
            }
        }
        return $actions;
    }

    /**
     * Add the hook to initialize the different post types
     */
    public function initialize(): void
    {
        parent::initialize();

        /**
         * Two outputs:
         * 1.`isGraphQLQueryExecution` = true, then resolve the GraphQL query
         * 2.`isGraphQLQueryExecution` = false, then do something else (eg: view the source for the GraphQL query)
         */
        if ($this->isGraphQLQueryExecution()) {
            $this->executeGraphQLQuery();
        } else {
            $this->doSomethingElse();
        }
    }

    /**
     * Do something else, not the execution of the GraphQL query.
     * By default, print the Query source
     */
    protected function doSomethingElse(): void
    {
        /** Add the excerpt, which is the description of the GraphQL query */
        \add_filter(
            'the_content',
            [$this, 'maybeGetGraphQLQuerySourceContent']
        );
    }

    /**
     * Render the GraphQL Query CPT
     */
    public function maybeGetGraphQLQuerySourceContent(string $content): string
    {
        /**
         * Check if it is this CPT...
         */
        if (\is_singular($this->getCustomPostType())) {
            $vars = ApplicationState::getVars();
            $customPost = $vars['routing-state']['queried-object'];
            // Make sure there is a post (eg: it has not been deleted)
            if ($customPost !== null) {
                return $this->getGraphQLQuerySourceContent($content, $customPost);
            }
        }
        return $content;
    }

    /**
     * Render the GraphQL Query CPT
     */
    protected function getGraphQLQuerySourceContent(string $content, WP_Post $graphQLQueryPost): string
    {
        /**
         * Prettyprint the code
         */
        $content .= '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
        return $content;
    }

    /**
     * Assign the single endpoint by setting it as the Home nature
     */
    public function getNature(string $nature, WP_Query $query): string
    {
        if ($query->is_singular($this->getCustomPostType())) {
            return $this->getUpstreamNature($nature, $query);
        }

        return $nature;
    }

    abstract protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock;

    /**
     * Read the options block and check the value of attribute "isEnabled"
     *
     * @param WP_Post|int $postOrID
     */
    protected function isOptionsBlockValueOn($postOrID, string $attribute, bool $default): bool
    {
        $optionsBlockDataItem = $this->getOptionsBlockDataItem($postOrID);
        // If there was no options block, something went wrong in the post content
        if (is_null($optionsBlockDataItem)) {
            return $default;
        }

        // The default value is not saved in the DB in Gutenberg!
        return $optionsBlockDataItem['attrs'][$attribute] ?? $default;
    }

    /**
     * Read the options block and check the value of attribute "isEnabled"
     *
     * @param WP_Post|int $postOrID
     */
    protected function isEnabled($postOrID): bool
    {
        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            AbstractQueryExecutionOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED,
            true
        );
    }

    /**
     * @param WP_Post|int $postOrID
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
     */
    protected function getOptionsBlockDataItem($postOrID): ?array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var BlockHelpers */
        $blockHelpers = $instanceManager->getInstance(BlockHelpers::class);
        return $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $this->getQueryExecutionOptionsBlock()
        );
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     *
     * @param WP_Post|int $postOrID
     */
    protected function doURLParamsOverrideGraphQLVariables($postOrID): bool
    {
        return true;
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @param array<array> $vars_in_array
     */
    public function addGraphQLVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        if (\is_singular($this->getCustomPostType()) && $this->isEnabled($vars['routing-state']['queried-object-id'])) {
            $this->upstreamAddGraphQLVars($vars_in_array);
        }
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array<array> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        $template = parent::getGutenbergTemplate();

        // If enabled by module, add the Schema Configuration block to the locked Gutenberg template
        $this->maybeAddSchemaConfigurationBlock($template);

        return $template;
    }

    /**
     * If enabled by module, add the Schema Configuration block to the locked Gutenberg template
     *
     * @param array<array> $template Every element is an array with template name in first pos, and attributes then
     */
    protected function maybeAddSchemaConfigurationBlock(array &$template): void
    {
        if ($this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var SchemaConfigurationBlock
             */
            $schemaConfigurationBlock = $instanceManager->getInstance(SchemaConfigurationBlock::class);
            $template[] = [$schemaConfigurationBlock->getBlockFullName()];
        }
    }
}
