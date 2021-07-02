<?php
namespace PoP\EngineHTMLCSSPlatform;

abstract class FunctionAPI_Base implements FunctionAPI
{
    public function __construct()
    {
        FunctionAPIFactory::setInstance($this);
    }

    public function getImagesDirectory()
    {
        return $this->getAssetsDirectory();
    }
    public function getImagesDirectoryURI()
    {
        return $this->getAssetsDirectoryURI();
    }
    public function getCSSAssetsDirectory()
    {
        return $this->getAssetsDirectory();
    }
    public function getCSSAssetsDirectoryURI()
    {
        return $this->getAssetsDirectoryURI();
    }
    public function getFontsDirectory()
    {
        return $this->getAssetsDirectory();
    }
    public function getFontsDirectoryURI()
    {
        return $this->getAssetsDirectoryURI();
    }
}
