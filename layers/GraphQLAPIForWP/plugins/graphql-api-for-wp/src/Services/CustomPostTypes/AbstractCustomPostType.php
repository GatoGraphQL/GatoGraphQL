<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\AppHelpers;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\Menus\MenuInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use PoP\Root\App;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;
use WP_Block_Editor_Context;
use WP_Post;
use WP_Taxonomy;

use function get_taxonomy;
use function is_object_in_taxonomy;
use function wp_dropdown_categories;

abstract class AbstractCustomPostType extends AbstractAutomaticallyInstantiatedService implements CustomPostTypeInterface
{
    use BasicServiceTrait;

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?CPTUtils $cptUtils = null;
    private ?PluginMenu $pluginMenu = null;
    private ?EndpointHelpers $endpointHelpers = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    final public function setCPTUtils(CPTUtils $cptUtils): void
    {
        $this->cptUtils = $cptUtils;
    }
    final protected function getCPTUtils(): CPTUtils
    {
        /** @var CPTUtils */
        return $this->cptUtils ??= $this->instanceManager->getInstance(CPTUtils::class);
    }
    final public function setPluginMenu(PluginMenu $pluginMenu): void
    {
        $this->pluginMenu = $pluginMenu;
    }
    final protected function getPluginMenu(): PluginMenu
    {
        /** @var PluginMenu */
        return $this->pluginMenu ??= $this->instanceManager->getInstance(PluginMenu::class);
    }
    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    /**
     * Add the hook to initialize the different post types
     */
    public function initialize(): void
    {
        /**
         * Only initialize once, for the main AppThread
         */
        if (!AppHelpers::isMainAppThread()) {
            return;
        }

        $postType = $this->getCustomPostType();
        // To satisfy the menu position, the CPT will be initialized
        // earlier or later
        \add_action(
            'init',
            $this->initCustomPostType(...),
            $this->getMenuPosition()
        );
        \add_action(
            'init',
            $this->maybeLockGutenbergTemplate(...)
        );
        /**
         * Starting from WP 5.8 the hook is a different one
         *
         * @see https://github.com/leoloso/PoP/issues/711
         */
        if (\is_wp_version_compatible('5.8')) {
            \add_filter(
                'allowed_block_types_all',
                $this->allowGutenbergBlocksForCustomPostTypeViaBlockEditorContext(...),
                10,
                2
            );
        } else {
            \add_filter(
                'allowed_block_types',
                $this->allowGutenbergBlocksForCustomPostType(...),
                10,
                2
            );
        }
        /**
         * Print the global JS variables, required by the blocks
         */
        \add_action(
            'admin_print_scripts',
            $this->printAdminGraphQLEndpointVariables(...)
        );

        /**
         * Add the excerpt, which is the description of the
         * different CPTs (GraphQL query/ACL/CCL)
         * */
        if ($this->isExcerptAsDescriptionEnabled() && $this->usePostExcerptAsDescription()) {
            // Execute last as to always add the description at the top
            \add_filter(
                'the_content',
                $this->maybeAddExcerptAsDescription(...),
                PHP_INT_MAX
            );
        }
        // Add the custom columns to the post type
        add_filter(
            "manage_{$postType}_posts_columns",
            $this->setTableColumns(...)
        );
        add_action(
            "manage_{$postType}_posts_custom_column",
            $this->resolveCustomColumn(...),
            10,
            2
        );
        add_action(
            "restrict_manage_posts",
            $this->restrictManageCustomPosts(...),
            10,
            2
        );

        /**
         * Add extra actions to the CPT table.
         * If they are hierarchical, they use hook "page_row_actions"
         */
        \add_filter(
            'post_row_actions',
            $this->maybeAddCustomPostTypeTableActions(...),
            10,
            2
        );
        \add_filter(
            'page_row_actions',
            $this->maybeAddCustomPostTypeTableActions(...),
            10,
            2
        );

        /**
         * Regenerate the timestamp when saving this CPT
         */
        if ($this->regenerateTimestampOnSave()) {
            // Ideally: Only do it if the post is published,
            // or was published and had its status changed
            // Otherwise, editing and saving a draft would also
            // update the timestamp, and there's no need for that
            // since it's not added to the schema
            // However, the old status is not provided through this hook,
            // so just check it's not being automatically saved in the editor
            // ("auto-draft" status)
            // This is also saving "draft" to "draft" for which there's no need,
            // but can't avoid it
            \add_action(
                "save_post_{$postType}",
                function ($postID, $post): void {
                    if ($post->post_status === 'auto-draft') {
                        return;
                    }
                    $this->getUserSettingsManager()->storeOperationalTimestamp();
                },
                10,
                2
            );
        }
    }

    /**
     * Print JS variables which are used by several blocks,
     * before the blocks are loaded
     */
    public function printAdminGraphQLEndpointVariables(): void
    {
        // Make sure the user has access to the editor
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }

        $scriptTag = '<script type="text/javascript">var %s = "%s"</script>';
        /**
         * The endpoint against which to execute GraphQL queries on the admin.
         * This GraphQL schema is modified by user preferences:
         * - Disabled types/directives are not in the schema
         * - Nested mutations enabled or not
         * - Schema namespaced or not
         * - etc
         */
        \printf(
            $scriptTag,
            'GRAPHQL_API_ADMIN_ENDPOINT',
            $this->getEndpointHelpers()->getAdminGraphQLEndpoint()
        );
        /**
         * The endpoint against which to execute GraphQL queries on the WordPress editor,
         * for Gutenberg blocks which require some field that must necessarily be enabled.
         * This GraphQL schema is not modified by user preferences:
         * - All types/directives are always in the schema
         * - The "admin" fields are in the schema
         * - Nested mutations enabled, without removing the redundant fields in the Root
         * - No namespacing
         */
        \printf(
            $scriptTag,
            'GRAPHQL_API_PLUGIN_OWN_USE_ADMIN_ENDPOINT',
            $this->getEndpointHelpers()->getAdminPluginOwnUseGraphQLEndpoint()
        );
    }

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->getModuleRegistry()->isModuleEnabled($enablingModule);
        }
        return parent::isServiceEnabled();
    }

    /**
     * Indicate if, whenever this CPT is created/updated,
     * the timestamp must be regenerated
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return false;
    }

    /**
     * @param array<string,string> $columns
     * @return array<string,string>
     */
    public function setTableColumns(array $columns): array
    {
        if (!($this->isExcerptAsDescriptionEnabled() && $this->usePostExcerptAsDescription())) {
            return $columns;
        }
        // Add the description column after the title
        $titlePos = array_search('title', array_keys($columns));
        return array_merge(
            array_slice(
                $columns,
                0,
                $titlePos + 1,
                true
            ),
            [
                'description' => \__('Description', 'graphql-api'),
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
            case 'description':
                /**
                 * @var WP_Post|null
                 */
                $post = \get_post($post_id);
                if ($post === null) {
                    break;
                }
                echo $this->getCPTUtils()->getCustomPostDescription($post);
                break;
        }
    }

    /**
     * Print styles, and filter by taxonomies
     *
     * @see wp-admin/includes/class-wp-posts-list-table.php
     */
    public function restrictManageCustomPosts(string $customPostType, string $which): void
    {
        if ($customPostType !== $this->getCustomPostType()) {
            return;
        }
        if ($which !== 'top') {
            return;
        }

        /**
         * Print table column styles for widths
         */
        $this->printTableStyles();

        /**
         * Print taxonomies filter
         */
        $taxonomies = $this->getTaxonomies();
        if ($taxonomies === []) {
            return;
        }
        foreach ($taxonomies as $taxonomy) {
            // Skip tags, only categories
            if (!$taxonomy->isHierarchical()) {
                continue;
            }
            $this->printTaxonomyDropdowns($taxonomy);
        }
    }

    protected function printTableStyles(): void
    {
        ?>
            <style type="text/css">
                .fixed .column-description {
                    width: 30%;
                }
            </style>
        <?php
    }

    /**
     * Based on function `categories_dropdown`
     *
     * @see function `categories_dropdown` in wp-admin/includes/class-wp-posts-list-table.php
     */
    protected function printTaxonomyDropdowns(TaxonomyInterface $taxonomy): void
    {
        // global $cat;
        $post_type = $this->getCustomPostType();

        /**
         * Filters whether to remove the 'Categories' drop-down from the post list table.
         *
         * @since 4.6.0
         *
         * @param bool   $disable   Whether to disable the categories drop-down. Default false.
         * @param string $post_type Post type slug.
         */
        if (false !== apply_filters('disable_categories_dropdown', false, $post_type)) {
            return;
        }

        $taxonomyName = $taxonomy->getTaxonomy();
        if (is_object_in_taxonomy($post_type, $taxonomyName)) {
            /** @var WP_Taxonomy */
            $taxonomyObject = get_taxonomy($taxonomyName);
            $dropdown_options = array(
                'show_option_all' => $taxonomyObject->labels->all_items,
                'hide_empty'      => 0,
                'hierarchical'    => 1,
                'show_count'      => 0,
                'orderby'         => 'name',
                // 'selected'        => $cat,
                'taxonomy'        => $taxonomyName,
                'name'            => $taxonomyName,
                'value_field'     => 'slug',
            );

            echo '<label class="screen-reader-text" for="' . $taxonomyName . '">' . $taxonomyObject->labels->filter_by_item . '</label>';

            wp_dropdown_categories($dropdown_options);
        }
    }

    /**
     * Add extra actions to the Custom Post Type list
     *
     * @see https://developer.wordpress.org/reference/hooks/post_row_actions/
     * @param array<string,string> $actions
     * @return array<string,string>
     */
    public function maybeAddCustomPostTypeTableActions(array $actions, WP_Post $post): array
    {
        if ($post->post_type === $this->getCustomPostType()) {
            $actions = \array_merge(
                $actions,
                $this->getCustomPostTypeTableActions($post)
            );
        }
        return $actions;
    }

    /**
     * Get actions to add for this CPT
     *
     * @return array<string,string>
     */
    protected function getCustomPostTypeTableActions(WP_Post $post): array
    {
        return [];
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return false;
    }

    /**
     * Block align class
     */
    public function getAlignClassName(): string
    {
        return 'aligncenter';
    }

    /**
     * Render the excerpt as the description for the current CPT
     * Can enable/disable through environment variable
     */
    public function maybeAddExcerptAsDescription(string $content): string
    {
        // Check if it is enabled and it is this CPT...
        if (
            !$this->getUserAuthorization()->canAccessSchemaEditor()
            || !\is_singular($this->getCustomPostType())
        ) {
            return $content;
        }

        // Make sure there is a post (eg: it has not been deleted)
        $customPost = App::getState(['routing', 'queried-object']);
        if ($customPost === null) {
            return $content;
        }

        // Check the excerpt is not empty
        $excerpt = $this->getCPTUtils()->getCustomPostDescription($customPost);
        if (empty($excerpt)) {
            return $content;
        }

        // Add the excerpt as description of the GraphQL query
        return \sprintf(
            \__('<p class="%s"><strong>Description: </strong>%s</p>'),
            $this->getAlignClassName(),
            $excerpt
        ) . $content;
    }

    /**
     * Custom Post Type singular name
     */
    abstract protected function getCustomPostTypeName(): string;

    /**
     * Custom Post Type under which it will be registered
     * From documentation: Max. 20 characters and may only contain lowercase alphanumeric characters,
     * dashes, and underscores.
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#Parameters
     */
    public function getCustomPostType(): string
    {
        return strtolower(str_replace(' ', '-', $this->getCustomPostTypeName()));
    }
    /**
     * Custom Post Type plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $titleCase): string
    {
        $postTypeName = $this->getCustomPostTypeName();
        if ($titleCase) {
            return $postTypeName;
        }
        return strtolower($postTypeName);
    }

    /**
     * Indicate if to make the Custom Post Type public.
     */
    abstract protected function isPublic(): bool;

    protected function isPubliclyQueryable(): bool
    {
        return $this->isPublic();
    }

    /**
     * Is the excerpt used as description for the CPT?
     */
    protected function isExcerptAsDescriptionEnabled(): bool
    {
        return $this->getModuleRegistry()->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::EXCERPT_AS_DESCRIPTION);
    }

    /**
     * Is the API Hierarchy Module enabled?
     */
    protected function isAPIHierarchyModuleEnabled(): bool
    {
        return $this->getModuleRegistry()->isModuleEnabled(EndpointConfigurationFunctionalityModuleResolver::API_HIERARCHY);
    }

    /**
     * Hierarchical
     */
    protected function isHierarchical(): bool
    {
        return false;
    }

    /**
     * Show in admin bar
     */
    protected function showInAdminBar(): bool
    {
        return false;
    }

    /**
     * The position on which to add the CPT on the menu.
     * This number will be used to initialize the CPT earlier or later
     */
    protected function getMenuPosition(): int
    {
        return 100;
    }

    /**
     * If provided, rewrite the slug
     */
    protected function getSlugBase(): ?string
    {
        return null;
    }

    /**
     * Arguments for registering the post type
     *
     * @return array<string,mixed>
     */
    protected function getCustomPostTypeArgs(): array
    {
        $name_uc = $this->getCustomPostTypeName();
        $names_uc = $this->getCustomPostTypePluralNames(true);
        $names_lc = $this->getCustomPostTypePluralNames(false);

        /**
         * This plugin's Configuration CPTs (eg: SchemaConfig,
         * ACLs, CCLs, etc) are to configure data,
         * and not to be directly accessible by themselves.
         *
         * Then, do not make them public, but still allow to access them.
         *
         * This way, executing query:
         *
         *   { customPosts(customPostTypes:["graphql-schemaconfig"]) }
         *
         * ...will fail, and we execute instead:
         *
         *   { schemaConfigurations }
         *
         * which can be @validated
         */
        $securityPostTypeArgs = [
            'public' => $this->isPublic(),
            'publicly_queryable' => $this->isPubliclyQueryable(),
        ];
        $canAccessSchemaEditor = $this->getUserAuthorization()->canAccessSchemaEditor();
        /** @var array<string,mixed> */
        $postTypeArgs = array_merge(
            $securityPostTypeArgs,
            [
                'label' => $this->getCustomPostTypeName(),
                'labels' => $this->getCustomPostTypeLabels($name_uc, $names_uc, $names_lc),
                'capability_type' => $canAccessSchemaEditor ? 'post' : '',
                'hierarchical' => $this->isAPIHierarchyModuleEnabled() && $this->isHierarchical(),
                'exclude_from_search' => true,
                'show_in_admin_bar' => $this->showInAdminBar(),
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_in_menu' => $canAccessSchemaEditor ? $this->getMenu()->getName() : false,
                'show_in_rest' => $canAccessSchemaEditor,
                'supports' => [
                    'title',
                    'editor',
                    // 'author',
                    'revisions',
                    // 'custom-fields',
                ],
            ]
        );
        if ($slugBase = $this->getSlugBase()) {
            $postTypeArgs['rewrite'] = ['slug' => $slugBase];
        }
        if ($taxonomies = $this->getTaxonomies()) {
            $postTypeArgs['taxonomies'] = array_map(
                fn (TaxonomyInterface $taxonomy) => $taxonomy->getTaxonomy(),
                $taxonomies
            );
        }
        if ($this->isAPIHierarchyModuleEnabled() && $this->isHierarchical()) {
            $postTypeArgs['supports'][] = 'page-attributes';
        }
        if ($this->isExcerptAsDescriptionEnabled() && $this->usePostExcerptAsDescription()) {
            $postTypeArgs['supports'][] = 'excerpt';
        }
        if ($template = $this->getGutenbergTemplate()) {
            $postTypeArgs['template'] = $template;
        }
        return $postTypeArgs;
    }

    public function getMenu(): MenuInterface
    {
        return $this->getPluginMenu();
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array<string,string>
     */
    protected function getCustomPostTypeLabels(string $name_uc, string $names_uc, string $names_lc): array
    {
        return array(
            'name'               => $names_uc,
            'singular_name'      => $name_uc,
            'add_new'            => sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'add_new_item'       => sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'edit_item'          => sprintf(\__('Edit %s', 'graphql-api'), $name_uc),
            'new_item'           => sprintf(\__('New %s', 'graphql-api'), $name_uc),
            'all_items'          => $names_uc,//sprintf(\__('All %s', 'graphql-api'), $names_uc),
            'view_item'          => sprintf(\__('View %s', 'graphql-api'), $name_uc),
            'search_items'       => sprintf(\__('Search %s', 'graphql-api'), $names_uc),
            'not_found'          => sprintf(\__('No %s found', 'graphql-api'), $names_lc),
            'not_found_in_trash' => sprintf(\__('No %s found in Trash', 'graphql-api'), $names_lc),
            'parent_item_colon'  => sprintf(\__('Parent %s:', 'graphql-api'), $name_uc),
        );
    }

    /**
     * Initialize the post type
     */
    public function initCustomPostType(): void
    {
        $this->registerCustomPostType();
    }

    /**
     * Register the post type
     */
    public function registerCustomPostType(): void
    {
        \register_post_type($this->getCustomPostType(), $this->getCustomPostTypeArgs());
    }

    /**
     * Unregister the post type
     */
    public function unregisterCustomPostType(): void
    {
        \unregister_post_type($this->getCustomPostType());
    }

    /**
     * Taxonomies
     *
     * @return TaxonomyInterface[]
     */
    protected function getTaxonomies(): array
    {
        return [];
    }

    /**
     * Lock down the Custom Post Type to use the given Gutenberg templates
     *
     * @see https://developer.wordpress.org/block-editor/developers/block-api/block-templates/#locking
     */
    public function maybeLockGutenbergTemplate(): void
    {
        if (!empty($this->getGutenbergTemplate()) && $this->lockGutenbergTemplate()) {
            $post_type_object = \get_post_type_object($this->getCustomPostType());
            if (!is_null($post_type_object)) {
                $post_type_object->template_lock = 'all';
            }
        }
    }

    /**
     * Restrict the Gutenberg blocks available for this Custom Post Type
     *
     * @param string[]|bool $allowedBlocks The list of blocks, or `true` for all of them
     * @return string[]|bool The list of blocks, or `true` for all of them
     */
    public function allowGutenbergBlocksForCustomPostTypeViaBlockEditorContext(array|bool $allowedBlocks, WP_Block_Editor_Context $blockEditorContext): array|bool
    {
        if ($blockEditorContext->post === null) {
            return $allowedBlocks;
        }
        return $this->allowGutenbergBlocksForCustomPostType(
            $allowedBlocks,
            $blockEditorContext->post
        );
    }

    /**
     * Restrict the Gutenberg blocks available for this Custom Post Type
     *
     * @param string[]|bool $allowedBlocks The list of blocks, or `true` for all of them
     * @return string[]|bool The list of blocks, or `true` for all of them
     */
    public function allowGutenbergBlocksForCustomPostType(array|bool $allowedBlocks, WP_Post $post): array|bool
    {
        /**
         * Check if it is this CPT
         */
        if ($post->post_type === $this->getCustomPostType()) {
            if ($blocks = $this->getGutenbergBlocksForCustomPostType()) {
                return $blocks;
            }
        }
        return $allowedBlocks;
    }

    /**
     * By default, if providing a template, then restrict the CPT to the blocks involved in the template
     *
     * @return string[] The list of block names
     */
    protected function getGutenbergBlocksForCustomPostType(): array
    {
        /**
         * If the CPT defined a template, then maybe restrict to those blocks
         */
        $template = $this->getGutenbergTemplate();
        if (!empty($template) && $this->enableOnlyGutenbergTemplateBlocks()) {
            // Get all the blocks involved in the template
            return array_values(array_unique(array_map(
                function (array $blockConfiguration) {
                    // The block is the first item from the $blockConfiguration
                    return $blockConfiguration[0];
                },
                $template
            )));
        }
        return [];
    }

    /**
     * Indicate if to restrict the blocks for the current post type to those involved in the template
     *
     * @return boolean `true` by default
     */
    protected function enableOnlyGutenbergTemplateBlocks(): bool
    {
        return true;
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array<string[]> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        return [];
    }

    /**
     * Indicates if to lock the Gutenberg templates
     */
    protected function lockGutenbergTemplate(): bool
    {
        return false;
    }
}
