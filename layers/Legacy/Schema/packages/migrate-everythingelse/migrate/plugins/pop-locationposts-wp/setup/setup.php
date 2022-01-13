<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_LocationPostsWP_Setup
{
    public function __construct()
    {
        register_activation_hook(
            POP_LOCATIONPOSTSWP_BASE,
            array($this, 'rewriteFlush')
        );
    
        HooksAPIFacade::getInstance()->addAction(
            'init',
            array($this, 'installPostType')
        );
    }

    public function rewriteFlush()
    {
        // Following instructions from https://codex.wordpress.org/Function_Reference/register_post_type
        $this->installPostType();
        flush_rewrite_rules();
    }

    public function installTaxonomy()
    {
        $name_uc = PoP_LocationPosts_PostNameUtils::getNameUc();
        $name_lc = PoP_LocationPosts_PostNameUtils::getNameLc();

        $labels = array(
            'name'                           => sprintf(TranslationAPIFacade::getInstance()->__('%s Categories', 'pop-locationposts-wp'), $name_uc),
            'singular_name'                  => sprintf(TranslationAPIFacade::getInstance()->__('%s Category', 'pop-locationposts-wp'), $name_uc),
            'search_items'                   => sprintf(TranslationAPIFacade::getInstance()->__('Search %s Categories', 'pop-locationposts-wp'), $name_uc),
            'all_items'                      => sprintf(TranslationAPIFacade::getInstance()->__('All %s Categories', 'pop-locationposts-wp'), $name_uc),
            'edit_item'                      => sprintf(TranslationAPIFacade::getInstance()->__('Edit %s Category', 'pop-locationposts-wp'), $name_uc),
            'update_item'                    => sprintf(TranslationAPIFacade::getInstance()->__('Update %s Category', 'pop-locationposts-wp'), $name_uc),
            'add_new_item'                   => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s Category', 'pop-locationposts-wp'), $name_uc),
            'new_item_name'                  => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s Category', 'pop-locationposts-wp'), $name_uc),
            'menu_name'                      => sprintf(TranslationAPIFacade::getInstance()->__('%s Category', 'pop-locationposts-wp'), $name_uc),
            'view_item'                      => sprintf(TranslationAPIFacade::getInstance()->__('View %s Category', 'pop-locationposts-wp'), $name_uc),
            'popular_items'                  => sprintf(TranslationAPIFacade::getInstance()->__('Popular %s categories', 'pop-locationposts-wp'), $name_lc),
            'separate_items_with_commas'     => sprintf(TranslationAPIFacade::getInstance()->__('Separate %s categories with commas', 'pop-locationposts-wp'), $name_lc),
            'add_or_remove_items'            => sprintf(TranslationAPIFacade::getInstance()->__('Add or remove %s category', 'pop-locationposts-wp'), $name_lc),
            'choose_from_most_used'          => sprintf(TranslationAPIFacade::getInstance()->__('Choose from the most used %s categories', 'pop-locationposts-wp'), $name_lc),
            'not_found'                      => sprintf(TranslationAPIFacade::getInstance()->__('No %s categories found', 'pop-locationposts-wp'), $name_lc),
        );
        $args = array(
            'label' => TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-wp'),
            'labels' => $labels,
            'hierarchical' => true,
            'public' => false,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud' => false,
        );

        register_taxonomy(
            POP_LOCATIONPOSTS_TAXONOMY_CATEGORY,
            POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST,
            $args
        );
    }

    public function installPostType()
    {
        // First install the taxonomy
        $this->installTaxonomy();

        $name_uc = PoP_LocationPosts_PostNameUtils::getNameUc();
        $names_uc = PoP_LocationPosts_PostNameUtils::getNamesUc();
        $names_lc = PoP_LocationPosts_PostNameUtils::getNamesLc();

        // Set the labels, this variable is used in the $args array
        $labels = array(
            'name'               => $names_uc,
            'singular_name'      => $name_uc,
            'add_new'            => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s', 'pop-locationposts-wp'), $name_uc),
            'add_new_item'       => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s', 'pop-locationposts-wp'), $name_uc),
            'edit_item'          => sprintf(TranslationAPIFacade::getInstance()->__('Edit %s', 'pop-locationposts-wp'), $name_uc),
            'new_item'           => sprintf(TranslationAPIFacade::getInstance()->__('New %s', 'pop-locationposts-wp'), $name_uc),
            'all_items'          => sprintf(TranslationAPIFacade::getInstance()->__('All %s', 'pop-locationposts-wp'), $names_uc),
            'view_item'          => sprintf(TranslationAPIFacade::getInstance()->__('View %s', 'pop-locationposts-wp'), $name_uc),
            'search_items'       => sprintf(TranslationAPIFacade::getInstance()->__('Search %s', 'pop-locationposts-wp'), $names_uc),
            'not_found'          => sprintf(TranslationAPIFacade::getInstance()->__('No %s found', 'pop-locationposts-wp'), $names_lc),
            'not_found_in_trash' => sprintf(TranslationAPIFacade::getInstance()->__('No %s found in Trash', 'pop-locationposts-wp'), $names_lc),
            'all_items'          => sprintf(TranslationAPIFacade::getInstance()->__('All %s', 'pop-locationposts-wp'), $names_uc),
        );

        // The arguments for our post type, to be entered as parameter 2 of register_post_type()
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'capability_type'        => 'post',
            'hierarchical'             => false,
            // 'rewrite'                 => true,
            'exclude_from_search'     => false,
            'supports'              => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'comments', 'custom-fields'),
            'show_in_admin_bar'     => false,
            'show_in_menu'            => true,
            'menu_position'            => 100,
            'query_var'                => true,
            'publicly_queryable'    => true,
            'taxonomies'              => array(POP_LOCATIONPOSTS_TAXONOMY_CATEGORY),
            'rewrite'                => array(
                'slug'                => POP_LOCATIONPOSTS_POSTTYPESLUG_LOCATIONPOST,
            ),
        );

        register_post_type(POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST, $args);
    }
}
    
/**
 * Initialize
 */
new PoP_LocationPostsWP_Setup();
