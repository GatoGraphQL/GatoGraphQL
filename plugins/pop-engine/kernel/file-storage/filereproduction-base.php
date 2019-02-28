<?php 
namespace PoP\Engine\FileStorage;

abstract class FileReproductionBase
{
    public function __construct()
    {
        if ($renderer = $this->getRenderer()) {
            $renderer->add($this);
        }
    }
    
    public function getRenderer()
    {
        return null;
    }
    
    public function getAssetsPath()
    {
        return '';
    }

    public function getConfiguration()
    {
        return array();
    }

    public function isJsonReplacement()
    {
        return true;
    }

    public function getJsonencodeOptions()
    {
        
        // Documentation: https://secure.php.net/manual/en/function.json-encode.php
        return 0;
    }
}
