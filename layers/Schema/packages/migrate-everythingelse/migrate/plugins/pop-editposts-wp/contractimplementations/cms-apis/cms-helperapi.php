<?php
namespace PoP\EditPosts\WP;

class HelperAPI extends \PoP\EditPosts\HelperAPI_Base
{
    public function kses($string, $allowed_html = null)
    {
        if (is_null($allowed_html)) {
            $cmseditpostsapi = \PoP\EditPosts\FunctionAPIFactory::getInstance();
            $allowed_html = $cmseditpostsapi->getAllowedPostTags();
        }
        return wp_kses($string, $allowed_html);
    }
}

/**
 * Initialize
 */
new HelperAPI();
