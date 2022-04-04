<?php

class PoPTheme_Wassup_EM_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor
{
    public final const RESOURCE_CSS_MAP = 'css-em-map';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CSS_MAP],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_CSS_MAP => 'map',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/'.$subpath.'templates/plugins/pop-locations';
    }
    
    // function getAssetPath(array $resource) {

    //     return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/templates/plugins/pop-locations/'.$this->getFilename($resource).'.css';
    // }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POPTHEME_WASSUPWEBPLATFORM_URL.'/css/'.$subpath.'templates/plugins/pop-locations';
    }
}


