<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

HooksAPIFacade::getInstance()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'popThemeModuleInstanceComponents');
function popThemeModuleInstanceComponents($components)
{
    
    if ($theme = \PoP\Root\App::getState('theme')) {
        $components[] = TranslationAPIFacade::getInstance()->__('theme:', 'pop-engine').$theme;
    }
    if ($thememode = \PoP\Root\App::getState('thememode')) {
        $components[] = TranslationAPIFacade::getInstance()->__('thememode:', 'pop-engine').$thememode;
    }
    if ($themestyle = \PoP\Root\App::getState('themestyle')) {
        $components[] = TranslationAPIFacade::getInstance()->__('themestyle:', 'pop-engine').$themestyle;
    }

    return $components;
}
