<?php
namespace PoP\Engine\WP;

class FunctionAPI extends \PoP\Engine\FunctionAPI_Base
{
    public function redirect($url)
    {
        wp_redirect($url);
    }

    public function getVersion()
    {
        return get_bloginfo('version');
    }

    public function getContentDir()
    {
        return WP_CONTENT_DIR;
    }

    public function getContentURL()
    {
        return WP_CONTENT_URL;
    }
}

/**
 * Initialize
 */
new FunctionAPI();
