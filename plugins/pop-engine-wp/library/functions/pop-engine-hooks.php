<?php
namespace PoP\Engine\WP;

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
        $vars['routing-state']['is-page'] = $nature == POP_NATURE_PAGE;
        $vars['routing-state']['is-single'] = $nature == POP_NATURE_SINGLE;
        $vars['routing-state']['is-author'] = $nature == POP_NATURE_AUTHOR;
        $vars['routing-state']['is-tag'] = $nature == POP_NATURE_TAG;
        $vars['routing-state']['is-category'] = $nature == POP_NATURE_CATEGORY;
    }
}

/**
 * Initialization
 */
new Engine_Hooks();
