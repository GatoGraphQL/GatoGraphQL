<?php
namespace PoP\EngineWebPlatform;

interface FunctionAPI
{
    public function getJSAssetsDirectory();
    public function getJSAssetsDirectoryURI();
    public function dequeueScript($handle);
    public function enqueueScript($handle, $src = '', $deps = array(), $ver = false, $in_footer = false);
    public function registerScript($handle, $src, $deps = array(), $ver = false, $in_footer = false);
    public function localizeScript($handle, $object_name, $l10n);
}
