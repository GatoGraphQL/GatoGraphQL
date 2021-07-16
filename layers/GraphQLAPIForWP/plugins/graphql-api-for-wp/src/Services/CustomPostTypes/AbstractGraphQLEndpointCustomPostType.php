<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Registries\EndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\EndpointExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\HooksAPIInterface;
use WP_Post;

abstract class AbstractGraphQLEndpointCustomPostType extends AbstractCustomPostType
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        protected HooksAPIInterface $hooksAPI
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $userAuthorization,
        );
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
                foreach ($this->getEndpointAnnotatorRegistry()->getEndpointAnnotators() as $endpointAnnotator) {
                    $endpointAnnotator->addCustomPostTypeTableActions($actions, $post);
                }
            }
        }

        return $actions;
    }

    abstract protected function getEndpointExecuterRegistry(): EndpointExecuterRegistryInterface;

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

        /**
         * Call it on "boot" after the WP_Query is parsed, so the single CPT
         * is loaded, and asking for `is_singular(CPT)` works.
         *
         * Important: load it before anything else, so it can load the hooks
         * from `executeGraphQLQuery` before these are called, which is
         * triggered also on "boot"
         */
        add_action(
            'popcms:boot',
            function (): void {
                /**
                 * Execute the EndpointExecuters from the Registry:
                 *
                 * Only 1 executer should be executed, from among (or other injected ones):
                 *
                 * - Query resolution
                 * - GraphiQL client
                 * - Voyager client
                 * - View query source
                 *
                 * All others will have `isServiceEnabled` => false, by checking
                 * their expected value of ?view=...
                 */
                foreach ($this->getEndpointExecuterRegistry()->getEndpointExecuters() as $endpointExecuter) {
                    $endpointExecuter->executeEndpoint();
                }
            },
            0
        );
    }

    /**
     * Hook to forbid access to the API
     */
    protected function isAccessForbidden(): bool
    {
        return $this->hooksAPI->applyFilters(
            Hooks::FORBID_ACCESS,
            false
        );
    }

    abstract protected function getEndpointOptionsBlock(): AbstractEndpointOptionsBlock;

    /**
     * Read the options block and check the value of attribute "isEndpointEnabled"
     */
    protected function isOptionsBlockValueOn(WP_Post|int $postOrID, string $attribute, bool $default): bool
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
     * Read the options block and check the value of attribute "isEndpointEnabled"
     */
    public function isEndpointEnabled(WP_Post|int $postOrID): bool
    {
        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            AbstractEndpointOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED,
            true
        );
    }

    /**
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
     */
    protected function getOptionsBlockDataItem(WP_Post|int $postOrID): ?array
    {
        /** @var BlockHelpers */
        $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
        return $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $this->getEndpointOptionsBlock()
        );
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     */
    protected function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        // If null, we are in the admin (eg: editing a Persisted Query),
        // and there's no need to override params
        return $customPost !== null;
    }
}
