<?php
namespace PoP\Theme\WP;

class FunctionAPI extends \PoP\Theme\FunctionAPI_Base
{
    public function getAssetsDirectory()
    {
        return get_stylesheet_directory();
    }
    public function getAssetsDirectoryURI()
    {
        return get_stylesheet_directory_uri();
    }
}

/**
 * Initialize
 */
new FunctionAPI();
