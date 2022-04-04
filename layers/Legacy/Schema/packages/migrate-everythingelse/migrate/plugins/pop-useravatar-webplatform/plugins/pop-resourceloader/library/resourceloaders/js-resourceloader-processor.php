<?php

class PoP_UserAvatarWebPlatform_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_USERAVATARACCOUNT = 'user-avatar-account';
    public final const RESOURCE_FILEUPLOAD = 'fileupload';
    public final const RESOURCE_FILEUPLOADLOCALE = 'fileupload-locale';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_USERAVATARACCOUNT],
            [self::class, self::RESOURCE_FILEUPLOAD],
            [self::class, self::RESOURCE_FILEUPLOADLOCALE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_FILEUPLOADLOCALE:
                return popUseravatarGetLocaleJsfilename();
        }
    
        $filenames = array(
            self::RESOURCE_USERAVATARACCOUNT => 'user-avatar-account',
            self::RESOURCE_FILEUPLOAD => 'fileupload',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_FILEUPLOADLOCALE:
                return '';
        }

        return parent::getSuffix($resource);
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_FILEUPLOADLOCALE:
                return popUseravatarGetLocaleJsfile();
        }

        return parent::getFileUrl($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERAVATARWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_FILEUPLOADLOCALE:
                return POP_USERAVATARWEBPLATFORM_DIR.'/js/locales/fileupload/';
        }

        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_USERAVATARWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_FILEUPLOADLOCALE:
                return POP_USERAVATARWEBPLATFORM_DIR.'/js/locales/fileupload/'.$this->getFilename($resource);
        }
    
        return POP_USERAVATARWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_USERAVATARWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_USERAVATARACCOUNT => array(
                'UserAvatarAccount',
            ),
            self::RESOURCE_FILEUPLOAD => array(
                'FileUpload',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
        
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_FILEUPLOAD:
                // Important: Keep the order below, or it produces a JS error when loading these libraries
                $dependencies[] = [PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::class, PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FILEUPLOAD];
                $dependencies[] = [PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::class, PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FILEUPLOADUI];
                $dependencies[] = [PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::class, PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FILEUPLOADPROCESS];
                $dependencies[] = [PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::class, PoP_UserAvatarProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FILEUPLOADVALIDATE];
                break;
        }

        return $dependencies;
    }

    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_FILEUPLOADLOCALE:
                $decorated[] = [self::class, self::RESOURCE_FILEUPLOAD];
                break;
        }

        return $decorated;
    }
}


