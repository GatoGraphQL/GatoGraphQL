<?php
namespace PoP\EngineHTMLCSSPlatform;

interface FunctionAPI
{
    public function getAssetsDirectory();
    public function getAssetsDirectoryURI();
    public function getImagesDirectory();
    public function getImagesDirectoryURI();
    public function getCSSAssetsDirectory();
    public function getCSSAssetsDirectoryURI();
    public function getFontsDirectory();
    public function getFontsDirectoryURI();
    public function dequeueStyle($handle);
    public function enqueueStyle($handle, $src = '', $deps = array(), $ver = false, $media = 'all');
    public function registerStyle($handle, $src, $deps = array(), $ver = false, $media = 'all');
    public function printFooterHTML();
    public function printHeadHTML();
}
