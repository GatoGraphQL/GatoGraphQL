<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PostTypes;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils;
use GraphQLAPI\GraphQLAPI\Services\Menus\Menu;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use WP_Post;

abstract class AbstractPostType
{
    protected Menu $menu;
    protected ModuleRegistryInterface $moduleRegistry;

    function __construct(
        Menu $menu,
        ModuleRegistryInterface $moduleRegistry
    ) {
        $this->menu = $menu;
        $this->moduleRegistry = $moduleRegistry;
    }
    /**
     * Add the hook to initialize the different post types
     *
     * @return void
     */
    public function initialize(): void
    {
        $postType = $this->getPostType();
        \add_action(
            'init',
            [$this, 'initPostType']
        );
        \add_action(
            'init',
            [$this, 'maybeLockGutenbergTemplate']
        );
        \add_filter(
            'allowed_block_types',
            [$this, 'allowGutenbergBlocksForCustomPostType'],
            10,
            2
        );

        /**
         * Add the excerpt, which is the description of the
         * different CPTs (GraphQL query/ACL/CCL)
         * */
        if ($this->isExcerptAsDescriptionEnabled() && $this->usePostExcerptAsDescription()) {
            // Execute last as to always add the description at the top
            \add_filter(
                'the_content',
                [$this, 'maybeAddExcerptAsDescription'],
                PHP_INT_MAX
            );
            // Add the custom columns to the post type
            add_filter(
                "manage_{$postType}_posts_columns",
                [$this, 'setTableColumns']
            );
            add_action(
                "manage_{$postType}_posts_custom_column",
                [$this, 'resolveCustomColumn'],
                10,
                2
            );
        }

        /**
         * Add extra actions to the CPT table.
         * If they are hierarchical, they use hook "page_row_actions"
         */
        \add_filter(
            'post_row_actions',
            [$this, 'maybeAddPostTypeTableActions'],
            10,
            2
        );
        \add_filter(
            'page_row_actions',
            [$this, 'maybeAddPostTypeTableActions'],
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
                    if ($post->post_status != 'auto-draft') {
                        $userSettingsManager = UserSettingsManagerFacade::getInstance();
                        $userSettingsManager->storeTimestamp();
                    }
                },
                10,
                2
            );
        }
    }

    /**
     * Indicate if, whenever this CPT is created/updated,
     * the timestamp must be regenerated
     *
     * @return boolean
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return false;
    }

    /**
     * @param array<string, string> $columns
     * @return array<string, string>
     */
    public function setTableColumns(array $columns): array
    {
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
                if (!is_null($post)) {
                    $instanceManager = InstanceManagerFacade::getInstance();
                    /** @var CPTUtils */
                    $cptUtils = $instanceManager->getInstance(CPTUtils::class);
                    echo $cptUtils->getCustomPostDescription($post);
                }
                break;
        }
    }

    /**
     * Add extra actions to the Custom Post Type list
     *
     * @see https://developer.wordpress.org/reference/hooks/post_row_actions/
     * @param array<string, string> $actions
     * @param WP_Post $post
     * @return array<string, string>
     */
    public function maybeAddPostTypeTableActions(array $actions, $post): array
    {
        if ($post->post_type == $this->getPostType()) {
            $actions = \array_merge(
                $actions,
                $this->getPostTypeTableActions($post)
            );
        }
        return $actions;
    }

    /**
     * Get actions to add for this CPT
     *
     * @param WP_Post $post
     * @return array<string, string>
     */
    protected function getPostTypeTableActions($post): array
    {
        return [];
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
     */
    public function usePostExcerptAsDescription(): bool
    {
        return false;
    }

    /**
     * Block align class
     */
    public function getAlignClass(): string
    {
        return 'aligncenter';
    }

    /**
     * Render the excerpt as the description for the current CPT
     * Can enable/disable through environment variable
     */
    public function maybeAddExcerptAsDescription(string $content): string
    {
        /**
         * Check if it is enabled and it is this CPT...
         */
        if (
            $this->moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::EXCERPT_AS_DESCRIPTION)
            && UserAuthorization::canAccessSchemaEditor()
            && \is_singular($this->getPostType())
        ) {
            /**
             * Add the excerpt (if not empty) as description of the GraphQL query
             */
            $vars = ApplicationState::getVars();
            $customPost = $vars['routing-state']['queried-object'];
            // Make sure there is a post (eg: it has not been deleted)
            if ($customPost !== null) {
                $instanceManager = InstanceManagerFacade::getInstance();
                /** @var CPTUtils */
                $cptUtils = $instanceManager->getInstance(CPTUtils::class);
                if ($excerpt = $cptUtils->getCustomPostDescription($customPost)) {
                    $content = \sprintf(
                        \__('<p class="%s"><strong>Description: </strong>%s</p>'),
                        $this->getAlignClass(),
                        $excerpt
                    ) . $content;
                }
            }
        }
        return $content;
    }

    /**
     * Custom Post Type singular name
     *
     * @return string
     */
    abstract protected function getPostTypeName(): string;
    /**
     * Custom Post Type under which it will be registered
     * From documentation: Max. 20 characters and may only contain lowercase alphanumeric characters,
     * dashes, and underscores.
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#Parameters
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return strtolower(str_replace(' ', '-', $this->getPostTypeName()));
    }
    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        $postTypeName = $this->getPostTypeName();
        if ($uppercase) {
            return $postTypeName;
        }
        return strtolower($postTypeName);
    }

    /**
     * Indicate if to make the Custom Post Type public.
     * By default it's false because, for configuration CPTs (Access Control Lists,
     * Cache Control Lists, Schema Configuration, etc), this data is private,
     * must not be exposed.
     *
     * @return boolean
     */
    protected function isPublic(): bool
    {
        return false;
    }

    /**
     * Is the excerpt used as description for the CPT?
     *
     * @return bool
     */
    protected function isExcerptAsDescriptionEnabled(): bool
    {
        return $this->moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::EXCERPT_AS_DESCRIPTION);
    }

    /**
     * Is the API Hierarchy Module enabled?
     *
     * @return bool
     */
    protected function isAPIHierarchyModuleEnabled(): bool
    {
        return $this->moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::API_HIERARCHY);
    }

    /**
     * Hierarchical
     *
     * @return bool
     */
    protected function isHierarchical(): bool
    {
        return false;
    }

    /**
     * Show in admin bar
     *
     * @return bool
     */
    protected function showInAdminBar(): bool
    {
        return false;
    }

    /**
     * If provided, rewrite the slug
     *
     * @return string|null
     */
    protected function getSlugBase(): ?string
    {
        return null;
    }

    /**
     * Arguments for registering the post type
     *
     * @return array<string, mixed>
     */
    protected function getPostTypeArgs(): array
    {
        $name_uc = $this->getPostTypeName();
        $names_uc = $this->getPostTypePluralNames(true);
        $names_lc = $this->getPostTypePluralNames(false);
        // Configuration CPTs are to configure data, and not to be directly accessible by themselves
        // Then, do not make them public, but still allow to access them
        // This way, executing query `{ posts(postTypes:["graphql-acl"]) }` will fail,
        // and we execute instead `{ accessControlLists }` which can be @validated
        $securityPostTypeArgs = array(
            'public' => $this->isPublic(),
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'publicly_queryable' => true,
        );
        $canAccessSchemaEditor = UserAuthorization::canAccessSchemaEditor();
        $postTypeArgs = array_merge(
            $securityPostTypeArgs,
            array(
                'label' => $this->getPostTypeName(),
                'labels' => $this->getPostTypeLabels($name_uc, $names_uc, $names_lc),
                'capability_type' => $canAccessSchemaEditor ? 'post' : '',
                'hierarchical' => $this->isAPIHierarchyModuleEnabled() && $this->isHierarchical(),
                'exclude_from_search' => true,
                'show_in_admin_bar' => $this->showInAdminBar(),
                'show_in_menu' => $canAccessSchemaEditor ? $this->menu->getName() : false,
                'show_in_rest' => true,
                'supports' => [
                    'title',
                    'editor',
                    // 'author',
                    'revisions',
                    // 'custom-fields',
                ],
            )
        );
        if ($slugBase = $this->getSlugBase()) {
            $postTypeArgs['rewrite'] = ['slug' => $slugBase];
        }
        if ($taxonomies = $this->getTaxonomies()) {
            $postTypeArgs['taxonomies'] = $taxonomies;
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
     *
     * @return void
     */
    public function initPostType(): void
    {
        $this->registerPostType();
    }

    /**
     * Register the post type
     *
     * @return void
     */
    public function registerPostType(): void
    {
        \register_post_type($this->getPostType(), $this->getPostTypeArgs());
    }

    /**
     * Unregister the post type
     *
     * @return void
     */
    public function unregisterPostType(): void
    {
        \unregister_post_type($this->getPostType());
    }

    /**
     * Taxonomies
     *
     * @return string[]
     */
    protected function getTaxonomies(): array
    {
        return [];
    }

    /**
     * Lock down the Custom Post Type to use the given Gutenberg templates
     *
     * @return void
     * @see https://developer.wordpress.org/block-editor/developers/block-api/block-templates/#locking
     */
    public function maybeLockGutenbergTemplate(): void
    {
        if (!empty($this->getGutenbergTemplate()) && $this->lockGutenbergTemplate()) {
            $post_type_object = \get_post_type_object($this->getPostType());
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
    public function allowGutenbergBlocksForCustomPostType($allowedBlocks, WP_Post $post)
    {
        /**
         * Check it is this CPT
         */
        if ($post->post_type == $this->getPostType()) {
            if ($blocks = $this->getGutenbergBlocksForCustomPostType()) {
                return $blocks;
            }
        }
        return $allowedBlocks;
    }

    /**
     * Comment: this function below to remove block types doesn't work,
     * because some of the most basic ones, such as "core/paragraph",
     * are never registered using `register_block_types`, then they can't be obtained
     * from `\WP_Block_Type_Registry::get_instance()->get_all_registered()`,
     * and this information exists nowhere.
     *
     * As a consequence, I am currently disabling blocks by assigning them a category
     * (Eg: "Access Control for GraphiQL") which is not registered for other CPTs
     * Unluckily, this produces an error on JavaScript:
     * > The block "graphql-api/access-control" must have a registered category.
     * > The block "graphql-api/access-control-disable-access" must have a registered category.
     * > ...
     *
     * But at least it works
     */
    // /**
    //  * Restrict the Gutenberg blocks available for this Custom Post Type
    //  *
    //  * @param [type] $allowedBlocks
    //  * @param [type] $post
    //  * @return array
    //  */
    // public function allowGutenbergBlocksForCustomPostType($allowedBlocks, $post)
    // {
    //     if ($blocks = $this->getGutenbergBlocksForCustomPostType()) {
    //         /**
    //          * Check if it is this CPT
    //          */
    //         if ($post->post_type == $this->getPostType()) {
    //             return $blocks;
    //         } elseif ($this->removeGutenbergBlocksForOtherPostTypes($post)) {
    //             // Remove this CPT's blocks from other post types.
    //             // $allowedBlocks can be a boolean. In that case, retrieve all blocks types, and substract the blocks
    //             if (!is_array($allowedBlocks)) {
    //                 $blockTypes = \WP_Block_Type_Registry::get_instance()->get_all_registered();
    //                 $allowedBlocks = array_keys($blockTypes);
    //             }
    //             $allowedBlocks = array_values(array_diff(
    //                 $allowedBlocks,
    //                 $blocks
    //             ));
    //         }
    //     }
    //     return $allowedBlocks;
    // }
    // /**
    //  * Indicate if to not allow this CPT's blocks in other Custom Post Types
    //  *
    //  * @param [type] $post
    //  * @return boolean
    //  */
    // protected function removeGutenbergBlocksForOtherPostTypes($post): bool
    // {
    //     return true;
    // }

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
     * @return array<array> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        return [];
    }

    /**
     * Indicates if to lock the Gutenberg templates
     *
     * @return boolean
     */
    protected function lockGutenbergTemplate(): bool
    {
        return false;
    }
}
