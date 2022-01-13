<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkersManager:manifest:icons',
            array($this, 'icons')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkersManager:manifest:theme_color',
            array($this, 'color')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkersManager:manifest:background_color',
            array($this, 'color')
        );
    }

    public function color($color)
    {
        if ($appcolor = HooksAPIFacade::getInstance()->applyFilters('PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color', '')) {
            return $appcolor;
        }
        
        return $color;
    }

    public function icons($icons)
    {
        $sizes = array(
            '48x48',
            '96x96',
            '192x192',
            '256x256',
            '512x512',
        );

        $imagename = HooksAPIFacade::getInstance()->applyFilters('PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:imagename', 'launcher-icon-');
        $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
        $path = $htmlcssplatformapi->getAssetsDirectoryURI().'/';

        foreach ($sizes as $size) {
            $icons[] = array(
                'src' => $path.$imagename.$size.'.png',
                'sizes' => $size,
                'type' => 'image/png',
            );
        }
        
        return $icons;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest();
