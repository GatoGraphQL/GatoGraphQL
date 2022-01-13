<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserStanceWP_Setup
{
    public function __construct()
    {
        register_activation_hook(
            POP_USERSTANCEWP_BASE,
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
        $labels = array(
            'name'                           => TranslationAPIFacade::getInstance()->__('Stances', 'pop-userstance-wp'),
            'singular_name'                  => TranslationAPIFacade::getInstance()->__('Stance', 'pop-userstance-wp'),
            'search_items'                   => TranslationAPIFacade::getInstance()->__('Search Stances', 'pop-userstance-wp'),
            'all_items'                      => TranslationAPIFacade::getInstance()->__('All Stances', 'pop-userstance-wp'),
            'edit_item'                      => TranslationAPIFacade::getInstance()->__('Edit Stance', 'pop-userstance-wp'),
            'update_item'                    => TranslationAPIFacade::getInstance()->__('Update Stance', 'pop-userstance-wp'),
            'add_new_item'                   => TranslationAPIFacade::getInstance()->__('Add New Stance', 'pop-userstance-wp'),
            'new_item_name'                  => TranslationAPIFacade::getInstance()->__('Add New Stance', 'pop-userstance-wp'),
            'menu_name'                      => TranslationAPIFacade::getInstance()->__('Stance', 'pop-userstance-wp'),
            'view_item'                      => TranslationAPIFacade::getInstance()->__('View Stance', 'pop-userstance-wp'),
            'popular_items'                  => TranslationAPIFacade::getInstance()->__('Popular stances', 'pop-userstance-wp'),
            'separate_items_with_commas'     => TranslationAPIFacade::getInstance()->__('Separate stances with commas', 'pop-userstance-wp'),
            'add_or_remove_items'            => TranslationAPIFacade::getInstance()->__('Add or remove stance', 'pop-userstance-wp'),
            'choose_from_most_used'          => TranslationAPIFacade::getInstance()->__('Choose from the most used stances', 'pop-userstance-wp'),
            'not_found'                      => TranslationAPIFacade::getInstance()->__('No stances found', 'pop-userstance-wp'),
        );
        $args = array(
            'label' => TranslationAPIFacade::getInstance()->__('Stances', 'pop-userstance-wp'),
            'labels' => $labels,
            'hierarchical' => true,
            'public' => false,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud' => false,
        );

        register_taxonomy(
            POP_USERSTANCE_TAXONOMY_STANCE,
            POP_USERSTANCE_POSTTYPE_USERSTANCE,
            $args
        );
    }

    public function installPostType()
    {
        // First install the taxonomy
        $this->installTaxonomy();

        $name_uc = PoP_UserStance_PostNameUtils::getNameUc();
        $names_uc = PoP_UserStance_PostNameUtils::getNamesUc();
        $names_lc = PoP_UserStance_PostNameUtils::getNamesLc();

        // Set the labels, this variable is used in the $args array
        $labels = array(
            'name'               => $names_uc,
            'singular_name'      => $name_uc,
            'add_new'            => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s', 'pop-userstance-wp'), $name_uc),
            'add_new_item'       => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s', 'pop-userstance-wp'), $name_uc),
            'edit_item'          => sprintf(TranslationAPIFacade::getInstance()->__('Edit %s', 'pop-userstance-wp'), $name_uc),
            'new_item'           => sprintf(TranslationAPIFacade::getInstance()->__('New %s', 'pop-userstance-wp'), $name_uc),
            'all_items'          => sprintf(TranslationAPIFacade::getInstance()->__('All %s', 'pop-userstance-wp'), $names_uc),
            'view_item'          => sprintf(TranslationAPIFacade::getInstance()->__('View %s', 'pop-userstance-wp'), $name_uc),
            'search_items'       => sprintf(TranslationAPIFacade::getInstance()->__('Search %s', 'pop-userstance-wp'), $names_uc),
            'not_found'          => sprintf(TranslationAPIFacade::getInstance()->__('No %s found', 'pop-userstance-wp'), $names_lc),
            'not_found_in_trash' => sprintf(TranslationAPIFacade::getInstance()->__('No %s found in Trash', 'pop-userstance-wp'), $names_lc),
            'all_items'          => sprintf(TranslationAPIFacade::getInstance()->__('All %s', 'pop-userstance-wp'), $names_uc),
        );

        // The arguments for our post type, to be entered as parameter 2 of register_post_type()
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'capability_type'        => 'post',
            'hierarchical'             => false,
            // 'rewrite'                 => true,
            'exclude_from_search'     => true,
            'supports'              => array('editor', 'author', 'comments', 'custom-fields'),
            'show_in_admin_bar'     => false,
            'show_in_menu'            => true,
            'menu_position'            => 100,
            'taxonomies'              => array(POP_USERSTANCE_TAXONOMY_STANCE),
            'rewrite'                => array(
                'slug'                => POP_USERSTANCE_POSTTYPESLUG_USERSTANCE,
            ),
        );

        register_post_type(POP_USERSTANCE_POSTTYPE_USERSTANCE, $args);
    }
}
    
/**
 * Initialize
 */
new PoP_UserStanceWP_Setup();
