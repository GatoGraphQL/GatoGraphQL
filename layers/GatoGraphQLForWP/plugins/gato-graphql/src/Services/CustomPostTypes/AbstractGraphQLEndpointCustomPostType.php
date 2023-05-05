<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\CustomPostTypes;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\Constants\HookNames;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Registries\EndpointAnnotatorRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointSchemaConfigurationBlock;
use GatoGraphQL\GatoGraphQL\Services\Helpers\BlockHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointBlockHelpers;
use PoP\Root\App;
use WP_Post;

abstract class AbstractGraphQLEndpointCustomPostType extends AbstractCustomPostType implements GraphQLEndpointCustomPostTypeInterface
{
    private ?BlockHelpers $blockHelpers = null;
    private ?EndpointBlockHelpers $endpointBlockHelpers = null;

    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        /** @var BlockHelpers */
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
    }
    final public function setEndpointBlockHelpers(EndpointBlockHelpers $endpointBlockHelpers): void
    {
        $this->endpointBlockHelpers = $endpointBlockHelpers;
    }
    final protected function getEndpointBlockHelpers(): EndpointBlockHelpers
    {
        /** @var EndpointBlockHelpers */
        return $this->endpointBlockHelpers ??= $this->instanceManager->getInstance(EndpointBlockHelpers::class);
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

    protected function isPublic(): bool
    {
        return true;
    }

    /**
     * Label to show on the "execute" action in the CPT table
     */
    protected function getExecuteActionLabel(): string
    {
        return __('Execute', 'gato-graphql');
    }

    /**
     * Get actions to add for this CPT
     * "View" action must be attached ?view=source, and the view link is called "Execute"
     *
     * @return array<string,string>
     */
    protected function getCustomPostTypeTableActions(WP_Post $post): array
    {
        $actions = parent::getCustomPostTypeTableActions($post);
        $post_type_object = \get_post_type_object($post->post_type);

        /**
         * Code copied from function `handle_row_actions` in file
         * wp-admin/includes/class-wp-posts-list-table.php
         */
        if ($post_type_object !== null && \is_post_type_viewable($post_type_object)) {
            $title = \_draft_or_post_title();
            $isEndpointEnabled = $this->isEndpointEnabled($post);
            $executeLabel = $this->getExecuteActionLabel();
            if (in_array($post->post_status, array('pending', 'draft', 'future'))) {
                $can_edit_post = \current_user_can('edit_post', $post->ID);
                if ($can_edit_post) {
                    $preview_link = \get_preview_post_link($post);
                    if ($preview_link !== null) {
                        $actions['view'] = sprintf(
                            '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                            \esc_url(\add_query_arg(
                                RequestParams::VIEW,
                                RequestParams::VIEW_SOURCE,
                                $preview_link
                            )),
                            /* translators: %s: Post title. */
                            \esc_attr(sprintf(__('Preview source &#8220;%s&#8221;', 'gato-graphql'), $title)),
                            __('Preview source', 'gato-graphql')
                        );
                        if ($isEndpointEnabled) {
                            $actions['execute'] = sprintf(
                                '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                                \esc_url($preview_link),
                                \esc_attr(sprintf(__('%s &#8220;%s&#8221;', 'gato-graphql'), $executeLabel, $title)),
                                $executeLabel
                            );
                        }
                    }
                }
            } elseif ('trash' !== $post->post_status) {
                if ($permalink = \get_permalink($post->ID)) {
                    $actions['view'] = sprintf(
                        '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                        \add_query_arg(
                            RequestParams::VIEW,
                            RequestParams::VIEW_SOURCE,
                            $permalink
                        ),
                        \esc_attr(sprintf(__('View source &#8220;%s&#8221;', 'gato-graphql'), $title)),
                        __('View source', 'gato-graphql')
                    );
                    if ($isEndpointEnabled) {
                        $actions['execute'] = sprintf(
                            '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                            $permalink,
                            \esc_attr(sprintf(__('%s &#8220;%s&#8221;', 'gato-graphql'), $executeLabel, $title)),
                            $executeLabel
                        );
                    }
                }
            }

            if ('trash' !== $post->post_status) {
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
                wp_die(\__('Access forbidden', 'gato-graphql'));
            }
        }, 0);
    }

    /**
     * Hook to forbid access to the API
     */
    protected function isAccessForbidden(): bool
    {
        return App::applyFilters(
            HookNames::FORBID_ACCESS,
            false,
            $this
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
     * @return array<string,mixed>|null Data inside the block is saved as key (string) => value
     */
    public function getOptionsBlockDataItem(WP_Post|int $postOrID): ?array
    {
        return $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $this->getEndpointOptionsBlock()
        );
    }

    /**
     * @param array<string,string> $columns
     * @return array<string,string>
     */
    public function setTableColumns(array $columns): array
    {
        $columns = parent::setTableColumns($columns);

        // Add the enabled column after the title
        $titlePos = array_search('title', array_keys($columns));
        return array_merge(
            array_slice(
                $columns,
                0,
                $titlePos + 1,
                true
            ),
            [
                'state' => \__('State', 'gato-graphql'),
                'schema-config' => \__('Schema Configuration', 'gato-graphql'),
            ],
            array_slice(
                $columns,
                $titlePos + 1,
                null,
                true
            )
        );
    }

    // Add the data to the custom columns for the book post type:
    public function resolveCustomColumn(string $column, int $post_id): void
    {
        switch ($column) {
            case 'state':
                /**
                 * @var WP_Post|null
                 */
                $post = \get_post($post_id);
                if ($post === null) {
                    break;
                }
                echo $this->isEndpointEnabled($post)
                    ? sprintf(
                        \__('✅ %s', 'gato-graphql'),
                        \__('Enabled', 'gato-graphql')
                    )
                    : sprintf(
                        \__('❌ %s', 'gato-graphql'),
                        \__('Disabled', 'gato-graphql')
                    );
                break;
            case 'schema-config':
                /** @var string */
                $enablingModule = $this->getEnablingModule();
                $schemaConfigurationID = $this->getEndpointBlockHelpers()->getSchemaConfigurationID(
                    $enablingModule,
                    $post_id,
                );
                if ($schemaConfigurationID === EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
                    _e('"None" selected', 'gato-graphql');
                    break;
                }
                if ($schemaConfigurationID === null) {
                    _e('(None)', 'gato-graphql');
                    break;
                }
                /** @var WP_Post */
                $schemaConfiguration = get_post($schemaConfigurationID);
                printf(
                    '<a href="%s">%s</a>',
                    get_edit_post_link($schemaConfigurationID),
                    $schemaConfiguration->post_title
                );
                break;
            default:
                parent::resolveCustomColumn($column, $post_id);
                break;
        }
    }

    protected function printTableStyles(): void
    {
        parent::printTableStyles();
        ?>
            <style type="text/css">
                .fixed .column-state {
                    width: 10%;
                }
                .fixed .column-schema-config {
                    width: 10%;
                }
            </style>
        <?php
    }
}
