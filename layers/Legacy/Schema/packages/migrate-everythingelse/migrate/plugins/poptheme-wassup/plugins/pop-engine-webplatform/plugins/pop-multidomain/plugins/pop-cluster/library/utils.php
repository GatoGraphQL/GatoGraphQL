<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_FrontEnd_MultiDomain_Cluster_Utils
{
    public static function init(): void
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_MultiDomain_Utils:transformUrl',
            array(PoPTheme_Wassup_FrontEnd_MultiDomain_Cluster_Utils::class, 'transformUrl'),
            10,
            5
        );
    }

    public static function transformUrl($transformed_url, $subpath, $url, $domain, $website_name)
    {

        // Replace the subpath /WEBSITE_NAME/ (after wp-content/pop-webplatform/) with the external website name
        return str_replace('/'.POP_WEBSITE.'/', '/'.$website_name.'/', $transformed_url);
    }
}

/**
 * Initialization
 */
PoPTheme_Wassup_FrontEnd_MultiDomain_Cluster_Utils::init();
