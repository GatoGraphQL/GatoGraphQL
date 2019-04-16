<?php
namespace PoP\CMSModel;

class Engine_Hooks
{
    public function __construct()
    {
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            'inferVarsProperties', 
            [$this, 'inferVarsProperties'], 
            10,
            1
        );
    }

    public function inferVarsProperties($vars_in_array)
    {

        // Set additional properties based on the nature: the global $post, $author, or $queried_object
        $vars = &$vars_in_array[0];
        $nature = $vars['nature'];

        // Attributes needed to match the RouteModuleProcessor vars conditions
        if ($nature == POP_NATURE_SINGLE) {
            $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
            $post_id = $vars['routing-state']['queried-object-id'];
            $vars['routing-state']['queried-object-post-type'] = $cmsapi->getPostType($post_id);
        }
    }
}

/**
 * Initialization
 */
new Engine_Hooks();
