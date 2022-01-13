<?php

class PoP_Bootstrap_ResourceLoader_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'getBootstrapFontUrl:pathkey',
            array($this, 'getPathkey')
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'getBootstrapFontPath',
            array($this, 'getFontPath'),
            10,
            2
        );
    }

    public function getPathkey($pathkey)
    {
        if (PoP_ResourceLoader_ServerUtils::useCodeSplitting() && PoP_ResourceLoader_ServerUtils::loadingBundlefile()) {
            return 'bundlefile';
        }
        
        return $pathkey;
    }

    public function getFontPath($font_path, $pathkey)
    {
        if ($pathkey == 'bundlefile') {
            $file = PoP_ResourceLoader_FileGenerator_BundleFiles_Utils::getFile(PoP_ResourceLoader_ServerUtils::getEnqueuefileType(true), POP_RESOURCELOADER_RESOURCETYPE_CSS, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
            // dirname: Up 1 level from folder where the bundle(group) files were generated
            return dirname($file->getUrl());
        }
        
        return $font_path;
    }
}


/**
 * Initialization
 */
new PoP_Bootstrap_ResourceLoader_Hooks();
