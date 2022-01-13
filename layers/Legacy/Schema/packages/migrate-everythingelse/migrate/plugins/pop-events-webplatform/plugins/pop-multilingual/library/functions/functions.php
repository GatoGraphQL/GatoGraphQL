<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Register the language scripts for the fullcalendar
\PoP\Root\App::getHookManager()->addAction("popcms:enqueueScripts", 'emPopprocessorsQtransxRegisterScripts');
function emPopprocessorsQtransxRegisterScripts()
{

    // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
    if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {

        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        // If the current lang is supported, then use fullcalendar's localization file
        if ($filename = getEmQtransxFullcalendarLocaleFilename()) {
            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                $placeholder = 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/lang/%s.js';
            } else {
                $placeholder = POP_EVENTSWEBPLATFORM_URL.'/js/includes/cdn/fullcalendar.3.8.2-lang/%s.js';
            }
            
            $js_file = sprintf(
                $placeholder,
                $filename
            );
            $cmswebplatformapi->registerScript('fullcalendar-lang', $js_file, array('fullcalendar'), null);
            $cmswebplatformapi->enqueueScript('fullcalendar-lang');
        }
    }
}

function getEmQtransxFullcalendarLocaleFilename()
{

    // List of supported languages in fullcalendar. Note that we have both 'zh-cn' and 'zh-tw', so this can be overriden
    // 'en' is hardcoded in the original file, so no need to handle (unless it must be overriden with other country, such as 'en-au')
    $languages = \PoP\Root\App::getHookManager()->applyFilters(
        'emPopprocessorsQtransxRegisterScripts:languages',
        array(
            'es' => 'es',
            'zh' => 'zh-cn',
        )
    );

    // If the current lang is supported, then use fullcalendar's localization file
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    if ($lang = $languages[$pluginapi->getCurrentLanguage()]) {
        return $lang;
    }

    // Default language
    return 'en-gb';
}
