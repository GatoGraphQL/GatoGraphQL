<?php

class PoP_PrettyPrint_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CODEPRETTIFY = 'external-code-prettify';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CODEPRETTIFY],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_CODEPRETTIFY => 'prettify',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_PRETTYPRINT_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CODEPRETTIFY:
                return POP_PRETTYPRINT_DIR.'/js/includes/cdn/google-code-prettify';
        }
    
        return POP_PRETTYPRINT_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CODEPRETTIFY:
                return $this->getDir($resource).'/prettify.js';
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CODEPRETTIFY:
                return '.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_CODEPRETTIFY:
                    return 'https://cdn.rawgit.com/google/code-prettify/master/loader';
            }
        }

        return POP_PRETTYPRINT_URL.'/js/includes/cdn/google-code-prettify';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CODEPRETTIFY:
                $dependencies[] = [PoP_PrettyPrint_VendorCSSResourceLoaderProcessor::class, PoP_PrettyPrint_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_CODEPRETTIFY];
                break;
        }

        return $dependencies;
    }
}


