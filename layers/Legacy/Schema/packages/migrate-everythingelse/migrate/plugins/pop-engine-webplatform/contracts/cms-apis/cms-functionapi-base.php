<?php
namespace PoP\EngineWebPlatform;

abstract class FunctionAPI_Base implements FunctionAPI
{
    public function __construct()
    {
        FunctionAPIFactory::setInstance($this);
    }

    public function getJSAssetsDirectory()
    {
        return $this->getAssetsDirectory();
    }
    public function getJSAssetsDirectoryURI()
    {
        return $this->getAssetsDirectoryURI();
    }
}
