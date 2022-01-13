<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Registries\EndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use WP_Post;

abstract class AbstractGraphQLEndpointCustomPostType extends AbstractCustomPostType implements GraphQLEndpointCustomPostTypeInterface
{
    private ?BlockHelpers $blockHelpers = null;

    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
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
    protected function getCustomPostTypeTableActions($post): array
    {
        $actions = parent::getCustomPostTypeTableActions($post);
        $post_type_object = \get_post_type_object($post->post_type);

        /**
         * Code copied from function `handle_row_actions` in file
         * wp-admin/includes/class-wp-posts-list-table.php
         */
        if (!is_null($post_type_object) && \is_post_type_viewable($post_type_object)) {
            $title = \_draft_or_post_title();
            $isEndpointEnabled = $this->isEndpointEnabled($post);
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
                        if ($isEndpointEnabled) {
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
                    if ($isEndpointEnabled) {
                        $actions['execute'] = sprintf(
                            '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                            $permalink,
                            esc_attr(sprintf(__('%s &#8220;%s&#8221;', 'graphql-api'), $executeLabel, $title)),
                            $executeLabel
                        );
                    }
                }

                // Let the EndpointAnnotators add their own actions
                foreach ($this->getEndpointAnnotatorRegistry()->getEnabledEndpointAnnotators() as $endpointAnnotator) {
                    $endpointAnnotator->addCustomPostTypeTableActions($actions, $post);
                }
            }
        }

        return $actions;
    }

    abstract protected function getEndpointAnnotatorRegistry(): EndpointAnnotatorRegistryInterface;

    /**
     * Add the hook to initialize the different post types
     */
    public function initialize(): void
    {
        parent::initialize();

        // Execute at the beginning. If access is forbidden, the process must end
        \add_action('init', function (): void {
            if ($this->isAccessForbidden()) {
                wp_die(\__('Access forbidden', 'graphql-api'));
            }
        }, 0);
    }

    /**
     * Hook to forbid access to the API
     */
    protected function isAccessForbidden(): bool
    {
        return App::getHookManager()->applyFilters(
            Hooks::FORBID_ACCESS,
            false
        );
    }

    /**
     * Read the options block and check the value of attribute "isEndpointEnabled"
     */
    protected function isOptionsBlockValueOn(WP_Post|int $postOrID, string $attribute, bool $default): bool
    {
        $optionsBlockDataItem = $this->getOptionsBlockDataItem($postOrID);
        // If there was no options block, something went wrong in the post content
        if ($optionsBlockDataItem === null) {
            return $default;
        }

        // The default value is not saved in the DB in Gutenberg!
        return $optionsBlockDataItem['attrs'][$attribute] ?? $default;
    }

    /**
     * Read the options block and check the value of attribute "isEndpointEnabled"
     */
    public function isEndpointEnabled(WP_Post|int $postOrID): bool
    {
        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            BlockAttributeNames::IS_ENABLED,
            true
        );
    }

    /**
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
     */
    public function getOptionsBlockDataItem(WP_Post|int $postOrID): ?array
    {
        return $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $this->getEndpointOptionsBlock()
        );
    }
}
