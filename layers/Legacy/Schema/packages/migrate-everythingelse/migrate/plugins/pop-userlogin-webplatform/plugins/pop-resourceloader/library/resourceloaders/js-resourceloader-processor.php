<?php

class PoP_UserLoginWebPlatform_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_USERACCOUNT = 'user-account';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_USERACCOUNT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_USERACCOUNT => 'user-account',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERLOGINWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_USERLOGINWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_USERLOGINWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_USERLOGINWEBPLATFORM_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_USERACCOUNT => array(
                'UserAccount',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
}


