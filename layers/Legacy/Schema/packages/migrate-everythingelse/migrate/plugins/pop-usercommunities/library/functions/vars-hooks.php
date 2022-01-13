<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::getHookManager()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'gdUreModuleInstanceComponents');
function gdUreModuleInstanceComponents($components)
{

    // Add source param for Communities: view their profile as Community or personal
    if (\PoP\Root\App::getState(['routing', 'is-user'])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        if (gdUreIsCommunity($author)) {
            if ($source = \PoP\Root\App::getState('source')) {
                $components[] = TranslationAPIFacade::getInstance()->__('source:', 'pop-usercommunities').$source;
            }
        }
    }

    return $components;
}
