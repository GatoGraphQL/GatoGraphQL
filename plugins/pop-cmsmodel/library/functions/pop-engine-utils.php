<?php
namespace PoP\CMSModel;

class Engine_Utils
{
    public static function reset()
    {

        // From the new URI set in $_SERVER['REQUEST_URI'], re-generate $vars
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $query = $cmsapi->getQueryFromRequestUri();
        \PoP\Engine\Engine_Vars::setQuery($query);
    }

    public static function calculateAndSetVarsStateReset($vars_in_array, $query)
    {

        // Set additional properties based on the hierarchy: the global $post, $author, or $queried_object
        $vars = &$vars_in_array[0];
        $hierarchy = $vars['hierarchy'];
        $has_queried_object_hierarchies = array(
        GD_SETTINGS_HIERARCHY_TAG,
        GD_SETTINGS_HIERARCHY_PAGE,
        GD_SETTINGS_HIERARCHY_SINGLE,
        GD_SETTINGS_HIERARCHY_AUTHOR,
        GD_SETTINGS_HIERARCHY_CATEGORY,
        );
        if (in_array($hierarchy, $has_queried_object_hierarchies)) {
            $vars['global-state']['queried-object'] = $query->get_queried_object();
            $vars['global-state']['queried-object-id'] = $query->get_queried_object_id();
        }
    }

    public static function calculateAndSetVarsState($vars_in_array, $query)
    {

        // Set additional properties based on the hierarchy: the global $post, $author, or $queried_object
        $vars = &$vars_in_array[0];
        $hierarchy = $vars['hierarchy'];

        // Attributes needed to match the PageModuleProcessor vars conditions
        if ($hierarchy == GD_SETTINGS_HIERARCHY_SINGLE) {
            $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
            $post_id = $vars['global-state']['queried-object-id'];
            $vars['global-state']['queried-object-post-type'] = $cmsapi->getPostType($post_id);
        }
    }
}

/**
 * Initialization
 */
add_action('\PoP\Engine\Engine_Vars:reset', array(Engine_Utils::class, 'reset'), 0); // Priority 0: execute immediately, to set the $query before anyone needs to use it
add_action('\PoP\Engine\Engine_Vars:calculateAndSetVarsState', array(Engine_Utils::class, 'calculateAndSetVarsState'), 0, 2);
add_action('\PoP\Engine\Engine_Vars:calculateAndSetVarsState:reset', array(Engine_Utils::class, 'calculateAndSetVarsStateReset'), 0, 2);
