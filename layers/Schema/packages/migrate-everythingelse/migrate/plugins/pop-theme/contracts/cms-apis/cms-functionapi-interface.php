<?php
namespace PoP\Theme;

interface FunctionAPI
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
}
