<?php
namespace PoP\CMS;

interface FrontendFunctionAPI extends FunctionAPI
{
    public function getAssetsDirectory();
    public function getAssetsDirectoryURI();
    public function getImagesDirectory();
    public function getImagesDirectoryURI();
    public function getJSAssetsDirectory();
    public function getJSAssetsDirectoryURI();
    public function getCSSAssetsDirectory();
    public function getCSSAssetsDirectoryURI();
    public function getFontsDirectory();
    public function getFontsDirectoryURI();
    public function dequeueScript($handle);
    public function dequeueStyle($handle);
    public function enqueueScript($handle, $src = '', $deps = array(), $ver = false, $in_footer = false);
    public function enqueueStyle($handle, $src = '', $deps = array(), $ver = false, $media = 'all');
    public function registerScript($handle, $src, $deps = array(), $ver = false, $in_footer = false);
    public function registerStyle($handle, $src, $deps = array(), $ver = false, $media = 'all');
    public function printFooterHTML();
    public function printHeadHTML();
    public function localizeScript($handle, $object_name, $l10n);
}
