<?php

class PoP_CoreProcessors_DynamicCSSResourceLoaderProcessor extends PoP_DynamicCSSResourceLoaderProcessor
{
    public final const RESOURCE_CSS_USERLOGGEDIN = 'user-loggedin';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CSS_USERLOGGEDIN],
        ];
    }
    
    public function getFilename(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CSS_USERLOGGEDIN:
                global $popcore_userloggedinstyles_file;
                return $popcore_userloggedinstyles_file->getFilename();
        }

        return parent::getFilename($resource);
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CSS_USERLOGGEDIN:
                global $popcore_userloggedinstyles_file;
                return $popcore_userloggedinstyles_file->getDir();
        }
    
        return parent::getDir($resource);
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CSS_USERLOGGEDIN:
                global $popcore_userloggedinstyles_file;
                return $popcore_userloggedinstyles_file->getFileurl();
        }

        return parent::getFileUrl($resource);
    }
}


