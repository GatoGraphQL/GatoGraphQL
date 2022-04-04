<?php

class PoP_Application_UserStance_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor
{
    public final const RESOURCE_CSS_VOTINGSTYLES = 'css-votingstyles';
    public final const RESOURCE_CSS_VOTINGLAYOUT = 'css-votinglayout';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CSS_VOTINGSTYLES],
            [self::class, self::RESOURCE_CSS_VOTINGLAYOUT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_CSS_VOTINGSTYLES => 'style',
            self::RESOURCE_CSS_VOTINGLAYOUT => 'voting-layout',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERSTANCEWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_USERSTANCEWEBPLATFORM_DIR.'/css/'.$subpath.'libraries';
    }
    
    // function getAssetPath(array $resource) {

    //     return POP_USERSTANCEWEBPLATFORM_DIR.'/css/libraries/'.$this->getFilename($resource).'.css';
    // }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_USERSTANCEWEBPLATFORM_URL.'/css/'.$subpath.'libraries';
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_CSS_VOTINGLAYOUT:
                $decorated[] = [PoPTheme_Wassup_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_CSSResourceLoaderProcessor::RESOURCE_CSS_LAYOUT];
                break;
        }

        return $decorated;
    }
}


