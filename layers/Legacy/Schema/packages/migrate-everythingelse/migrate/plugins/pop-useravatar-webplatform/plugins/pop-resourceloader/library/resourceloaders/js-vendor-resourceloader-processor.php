<?php

class PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_IFRAMETRANSPORT = 'external-iframe-transport';
    public final const RESOURCE_EXTERNAL_FILEUPLOAD = 'external-fileupload';
    public final const RESOURCE_EXTERNAL_FILEUPLOADUI = 'external-fileupload-ui';
    public final const RESOURCE_EXTERNAL_FILEUPLOADPROCESS = 'external-fileupload-process';
    public final const RESOURCE_EXTERNAL_FILEUPLOADVALIDATE = 'external-fileupload-validate';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_IFRAMETRANSPORT],
            [self::class, self::RESOURCE_EXTERNAL_FILEUPLOAD],
            [self::class, self::RESOURCE_EXTERNAL_FILEUPLOADUI],
            [self::class, self::RESOURCE_EXTERNAL_FILEUPLOADPROCESS],
            [self::class, self::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $version = (!PoP_WebPlatform_ServerUtils::accessExternalcdnResources() ? '.9.5.7' : '');
        $filenames = array(
            self::RESOURCE_EXTERNAL_IFRAMETRANSPORT => 'jquery.iframe-transport'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOAD => 'jquery.fileupload'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOADUI => 'jquery.fileupload-ui'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOADPROCESS => 'jquery.fileupload-process'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE => 'jquery.fileupload-validate'.$version,
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
        return POP_USERAVATARWEBPLATFORM_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        $version = '.9.5.7';
        $filenames = array(
            self::RESOURCE_EXTERNAL_IFRAMETRANSPORT => 'jquery.iframe-transport'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOAD => 'jquery.fileupload'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOADUI => 'jquery.fileupload-ui'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOADPROCESS => 'jquery.fileupload-process'.$version,
            self::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE => 'jquery.fileupload-validate'.$version,
        );
        if ($filename = $filenames[$resource[1]]) {
            return $this->getDir($resource).'/'.$filename.'.min.js';
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_IFRAMETRANSPORT:
            case self::RESOURCE_EXTERNAL_FILEUPLOAD:
            case self::RESOURCE_EXTERNAL_FILEUPLOADUI:
            case self::RESOURCE_EXTERNAL_FILEUPLOADPROCESS:
            case self::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE:
                return '.min.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            switch ($resource[1]) {
                case self::RESOURCE_EXTERNAL_IFRAMETRANSPORT:
                case self::RESOURCE_EXTERNAL_FILEUPLOAD:
                case self::RESOURCE_EXTERNAL_FILEUPLOADUI:
                case self::RESOURCE_EXTERNAL_FILEUPLOADPROCESS:
                case self::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE:
                    return 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7';
            }
        }

        return POP_USERAVATARWEBPLATFORM_URL.'/js/includes/cdn';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_FILEUPLOAD:
                $dependencies[] = [PoP_UserAvatarProcessors_VendorCSSResourceLoaderProcessor::class, PoP_UserAvatarProcessors_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_FILEUPLOAD];
                $dependencies[] = [self::class, self::RESOURCE_EXTERNAL_IFRAMETRANSPORT];
                break;

         // Make sure the UI is loaded first, or otherwise we get a JS error
            case self::RESOURCE_EXTERNAL_FILEUPLOADPROCESS:
            case self::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE:
                $dependencies[] = [self::class, self::RESOURCE_EXTERNAL_FILEUPLOADUI];
                break;
        }

        return $dependencies;
    }
    
    public function asyncLoadInOrder(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_IFRAMETRANSPORT:
            case self::RESOURCE_EXTERNAL_FILEUPLOAD:
            case self::RESOURCE_EXTERNAL_FILEUPLOADUI:
            case self::RESOURCE_EXTERNAL_FILEUPLOADPROCESS:
            case self::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE:
                return true;
        }

        return parent::asyncLoadInOrder($resource);
    }
}


