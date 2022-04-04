<?php

class PoP_BootstrapWebPlatform_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_BOOTSTRAP = 'css-external-bootstrap';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_BOOTSTRAP],
        ];
    }
    
    public function getHandle(array $resource)
    {
    
        // Other resources depend on bootstrap being called "bootstrap"
        $handles = array(
            self::RESOURCE_EXTERNAL_CSS_BOOTSTRAP => 'bootstrap',
        );
        if ($handle = $handles[$resource[1]]) {
            return $handle;
        }

        return parent::getHandle($resource);
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_BOOTSTRAP => 'bootstrap'.(!$use_cdn ? '.3.3.7' : ''),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POP_BOOTSTRAPWEBPLATFORM_DIR.'/css/includes/cdn';
    }
    
    public function getReferencedFiles(array $resource)
    {
        $referenced_files = parent::getReferencedFiles($resource);

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_BOOTSTRAP:
                $referenced_files[] = '../fonts/glyphicons-halflings-regular.eot';
                $referenced_files[] = '../fonts/glyphicons-halflings-regular.woff2';
                $referenced_files[] = '../fonts/glyphicons-halflings-regular.woff';
                $referenced_files[] = '../fonts/glyphicons-halflings-regular.ttf';
                $referenced_files[] = '../fonts/glyphicons-halflings-regular.svg';
                break;
        }

        return $referenced_files;
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $filenames = array(
                self::RESOURCE_EXTERNAL_CSS_BOOTSTRAP => 'bootstrap.3.3.7',
            );
            if ($filename = $filenames[$resource[1]]) {
                return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
            }
        }

        return parent::getAssetPath($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $paths = array(
                self::RESOURCE_EXTERNAL_CSS_BOOTSTRAP => 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        return POP_BOOTSTRAPWEBPLATFORM_URL.'/css/includes/cdn';
    }
}


