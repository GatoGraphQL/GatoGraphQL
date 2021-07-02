<?php
namespace PoP\EngineHTMLCSSPlatform\WP;

class FunctionAPI extends \PoP\EngineHTMLCSSPlatform\FunctionAPI_Base
{
    public function getAssetsDirectory()
    {
        return get_stylesheet_directory();
    }
    public function getAssetsDirectoryURI()
    {
        return get_stylesheet_directory_uri();
    }

    public function dequeueStyle($handle)
    {
        return wp_dequeue_style($handle);
    }

    public function enqueueStyle($handle, $src = '', $deps = array(), $ver = false, $media = 'all')
    {
        return wp_enqueue_style($handle, $src, $deps, $ver, $media);
    }

    public function registerStyle($handle, $src, $deps = array(), $ver = false, $media = 'all')
    {
        return wp_register_style($handle, $src, $deps, $ver, $media);
    }

    public function printFooterHTML()
    {
        wp_footer();
    }
    public function printHeadHTML()
    {
        wp_head();
    }
}

/**
 * Initialize
 */
new FunctionAPI();
