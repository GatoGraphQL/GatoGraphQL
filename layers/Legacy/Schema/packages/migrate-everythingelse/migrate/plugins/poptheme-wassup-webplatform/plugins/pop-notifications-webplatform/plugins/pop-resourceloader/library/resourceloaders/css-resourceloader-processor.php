<?php

class PopThemeWassup_AAL_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor
{
    public final const RESOURCE_CSS_NOTIFICATIONLAYOUT = 'css-notification-layout';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CSS_NOTIFICATIONLAYOUT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_CSS_NOTIFICATIONLAYOUT => 'notification-layout',
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
        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/'.$subpath.'templates/plugins/pop-notifications';
    }
    
    // function getAssetPath(array $resource) {

    //     return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/templates/plugins/pop-notifications/'.$this->getFilename($resource).'.css';
    // }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POPTHEME_WASSUPWEBPLATFORM_URL.'/css/'.$subpath.'templates/plugins/pop-notifications';
    }
}


