<?php
namespace PoP\EditPosts\WP;

class CustomCMSCodeFunctionAPI extends FunctionAPI
{
    protected function convertQueryArgsFromPoPToCMSForInsertUpdatePost(&$query)
    {
        parent::convertQueryArgsFromPoPToCMSForInsertUpdatePost($query);

        // WordPress-specific parameters!
        if (isset($query['menu-order'])) {

            $query['menu_order'] = $query['menu-order'];
            unset($query['menu-order']);
        }
    }
}

/**
 * Initialize
 */
if (!\PoP\Application\Environment::disableCustomCMSCode()) {
    new CustomCMSCodeFunctionAPI();
}
