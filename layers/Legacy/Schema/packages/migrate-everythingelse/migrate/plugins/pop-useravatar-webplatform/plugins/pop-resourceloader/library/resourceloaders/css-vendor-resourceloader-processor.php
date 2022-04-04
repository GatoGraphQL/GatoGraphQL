<?php

class PoP_UserAvatarProcessors_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_FILEUPLOAD = 'css-external-fileupload';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_FILEUPLOAD],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_FILEUPLOAD => 'jquery.fileupload'.(!$use_cdn ? '.9.5.7' : ''),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERAVATARWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POP_USERAVATARWEBPLATFORM_DIR.'/css/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $filenames = array(
                self::RESOURCE_EXTERNAL_CSS_FILEUPLOAD => 'jquery.fileupload.9.5.7',
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
                self::RESOURCE_EXTERNAL_CSS_FILEUPLOAD => 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/css',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        return POP_USERAVATARWEBPLATFORM_URL.'/css/includes/cdn';
    }
}


