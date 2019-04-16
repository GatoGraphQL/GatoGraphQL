<?php
namespace PoP\CMS\WP;

class FrontendFunctionAPI extends FunctionAPI implements \PoP\CMS\FrontendFunctionAPI
{
    use \PoP\CMS\FunctionAPI_Trait;

    public function getAssetsDirectory()
    {
        return get_stylesheet_directory();
    }
    public function getAssetsDirectoryURI()
    {
        return get_stylesheet_directory_uri();
    }

    public function dequeueScript($handle)
    {
        return wp_dequeue_script($handle);
    }

    public function dequeueStyle($handle)
    {
        return wp_dequeue_style($handle);
    }

    public function enqueueScript($handle, $src = '', $deps = array(), $ver = false, $in_footer = false)
    {
        return wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    }
    public function enqueueStyle($handle, $src = '', $deps = array(), $ver = false, $media = 'all')
    {
        return wp_enqueue_style($handle, $src, $deps, $ver, $media);
    }
    public function registerScript($handle, $src, $deps = array(), $ver = false, $in_footer = false)
    {
        return wp_register_script($handle, $src, $deps, $ver, $in_footer);
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

    public function localizeScript($handle, $object_name, $l10n)
    {
        return wp_localize_script($handle, $object_name, $l10n);
    }
}

/**
 * Initialize
 */
new FrontendFunctionAPI();
