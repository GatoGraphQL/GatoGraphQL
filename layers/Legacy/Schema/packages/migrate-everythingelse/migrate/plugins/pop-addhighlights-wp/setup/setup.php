<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlightsWP_Setup
{
    public function __construct()
    {
        register_activation_hook(
            POP_ADDHIGHLIGHTSWP_BASE,
            array($this, 'rewriteFlush')
        );
    
        \PoP\Root\App::getHookManager()->addAction(
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

    public function installPostType()
    {
        $name_uc = PoP_AddHighlights_PostNameUtils::getNameUc();
        $names_uc = PoP_AddHighlights_PostNameUtils::getNamesUc();
        $names_lc = PoP_AddHighlights_PostNameUtils::getNamesLc();

        // Set the labels, this variable is used in the $args array
        $labels = array(
            'name'               => $names_uc,
            'singular_name'      => $name_uc,
            'add_new'            => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s', 'pop-addhighlights-wp'), $name_uc),
            'add_new_item'       => sprintf(TranslationAPIFacade::getInstance()->__('Add New %s', 'pop-addhighlights-wp'), $name_uc),
            'edit_item'          => sprintf(TranslationAPIFacade::getInstance()->__('Edit %s', 'pop-addhighlights-wp'), $name_uc),
            'new_item'           => sprintf(TranslationAPIFacade::getInstance()->__('New %s', 'pop-addhighlights-wp'), $name_uc),
            'all_items'          => sprintf(TranslationAPIFacade::getInstance()->__('All %s', 'pop-addhighlights-wp'), $names_uc),
            'view_item'          => sprintf(TranslationAPIFacade::getInstance()->__('View %s', 'pop-addhighlights-wp'), $name_uc),
            'search_items'       => sprintf(TranslationAPIFacade::getInstance()->__('Search %s', 'pop-addhighlights-wp'), $names_uc),
            'not_found'          => sprintf(TranslationAPIFacade::getInstance()->__('No %s found', 'pop-addhighlights-wp'), $names_lc),
            'not_found_in_trash' => sprintf(TranslationAPIFacade::getInstance()->__('No %s found in Trash', 'pop-addhighlights-wp'), $names_lc),
            'all_items'          => sprintf(TranslationAPIFacade::getInstance()->__('All %s', 'pop-addhighlights-wp'), $names_uc),
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
            'rewrite'                => array(
                'slug'                => POP_ADDHIGHLIGHTS_POSTTYPESLUG_HIGHLIGHT,
            ),
            // 'query_var'                => true,
            // 'publicly_queryable'    => false,
        );

        register_post_type(POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT, $args);
    }
}
    
/**
 * Initialize
 */
new PoP_AddHighlightsWP_Setup();
