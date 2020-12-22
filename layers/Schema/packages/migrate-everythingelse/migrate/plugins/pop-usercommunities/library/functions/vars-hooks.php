<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

HooksAPIFacade::getInstance()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'gdUreModuleInstanceComponents');
function gdUreModuleInstanceComponents($components)
{

    // Add source param for Communities: view their profile as Community or personal
    $vars = ApplicationState::getVars();
    if ($vars['routing-state']['is-user']) {
        $author = $vars['routing-state']['queried-object-id'];
        if (gdUreIsCommunity($author)) {
            if ($source = $vars['source']) {
                $components[] = TranslationAPIFacade::getInstance()->__('source:', 'pop-usercommunities').$source;
            }
        }
    }

    return $components;
}
