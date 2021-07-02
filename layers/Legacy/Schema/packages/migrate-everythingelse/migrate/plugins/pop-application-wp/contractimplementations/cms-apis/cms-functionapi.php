<?php
namespace PoP\Application\WP;

class FunctionAPI extends \PoP\Application\FunctionAPI_Base
{
    public function isAdminPanel()
    {
        return is_admin();
    }

    public function getDocumentTitle()
    {
        return wp_get_document_title();
    }

    public function getSiteName()
    {
        return get_bloginfo('name');
    }

    public function getSiteDescription()
    {
        return get_bloginfo('description');
    }
}

/**
 * Initialize
 */
new FunctionAPI();
