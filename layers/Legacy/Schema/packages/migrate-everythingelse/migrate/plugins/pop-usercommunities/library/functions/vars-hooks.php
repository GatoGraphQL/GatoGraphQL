<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

HooksAPIFacade::getInstance()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'gdUreModuleInstanceComponents');
function gdUreModuleInstanceComponents($components)
{

    // Add source param for Communities: view their profile as Community or personal
    $vars = ApplicationState::getVars();
    if ($vars['routing']['is-user']) {
        $author = $vars['routing']['queried-object-id'];
        if (gdUreIsCommunity($author)) {
            if ($source = $vars['source']) {
                $components[] = TranslationAPIFacade::getInstance()->__('source:', 'pop-usercommunities').$source;
            }
        }
    }

    return $components;
}
