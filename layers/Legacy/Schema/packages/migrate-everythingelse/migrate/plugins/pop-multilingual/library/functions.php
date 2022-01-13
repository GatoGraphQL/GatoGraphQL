<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

function getMultilingualLanguageitems($shortnames = array())
{
    $items = array();

    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    $languages = $pluginapi->getEnabledLanguages();
    
    // Add the language links
    if ($languages && count($languages) > 1) {
        // Allow to hook in the list of shortnames
        if (!$shortnames) {
            $shortnames = \PoP\Root\App::applyFilters('getMultilingualLanguageitems:shortnames', array());
        }
        
        $current = $pluginapi->getCurrentLanguage();
        $cmsService = CMSServiceFacade::getInstance();
        $url = GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL());
        foreach ($languages as $lang) {
            $name = $pluginapi->getLanguageName($lang);
            $shortname = $shortnames[$lang] ? $shortnames[$lang] : $name;
            if ($current == $lang) {
                $items[] = $shortname;
            } else {
                $items[] = sprintf(
                    '<a href="%s" target="%s" title="%s">%s</a>',
                    $pluginapi->convertUrl($url, $lang),
                    GD_URLPARAM_TARGET_FULL,
                    $name,
                    $shortname
                );
            }
        }
    }

    return $items;
}
