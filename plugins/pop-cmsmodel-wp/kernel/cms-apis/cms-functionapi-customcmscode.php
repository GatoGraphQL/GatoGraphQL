<?php
namespace PoP\CMSModel\WP;

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
if (\PoP\Engine\Server\Utils::enableCustomCMSCode()) {
    new CustomCMSCodeFunctionAPI();
}
