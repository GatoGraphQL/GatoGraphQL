<?php
namespace PoP\EngineWebPlatform\WP;

class FunctionAPI extends \PoP\EngineWebPlatform\FunctionAPI_Base
{
    public function dequeueScript($handle)
    {
        return wp_dequeue_script($handle);
    }

    public function enqueueScript($handle, $src = '', $deps = array(), $ver = false, $in_footer = false)
    {
        return wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    }

    public function registerScript($handle, $src, $deps = array(), $ver = false, $in_footer = false)
    {
        return wp_register_script($handle, $src, $deps, $ver, $in_footer);
    }

    public function localizeScript($handle, $object_name, $l10n)
    {
        return wp_localize_script($handle, $object_name, $l10n);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
