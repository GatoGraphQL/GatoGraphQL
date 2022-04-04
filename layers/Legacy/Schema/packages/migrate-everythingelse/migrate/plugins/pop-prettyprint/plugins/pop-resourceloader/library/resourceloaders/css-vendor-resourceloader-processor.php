<?php

class PoP_PrettyPrint_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_CODEPRETTIFY = 'css-external-code-prettify';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_CODEPRETTIFY],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_CODEPRETTIFY => 'desert',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_CODEPRETTIFY:
                return '.css';
        }

        return parent::getSuffix($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_PRETTYPRINT_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POP_PRETTYPRINT_DIR.'/css/includes/cdn/google-code-prettify';
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $filenames = array(
                self::RESOURCE_EXTERNAL_CSS_CODEPRETTIFY => 'desert',
            );
            if ($filename = $filenames[$resource[1]]) {
                return $this->getDir($resource).'/'.$filename.'.css';
            }
        }

        return parent::getAssetPath($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $paths = array(
                self::RESOURCE_EXTERNAL_CSS_CODEPRETTIFY => 'https://cdn.rawgit.com/google/code-prettify/master/styles',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        return POP_PRETTYPRINT_URL.'/css/includes/cdn/google-code-prettify';
    }
}


