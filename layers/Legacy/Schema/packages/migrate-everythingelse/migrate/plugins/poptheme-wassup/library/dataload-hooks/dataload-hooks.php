<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('PoPWebPlatform_Engine:request-meta', 'addToplevelVarsSilent');
function addToplevelVarsSilent($meta)
{
    
    // Silent document? (Opposite to Update the browser URL and Title?)
    $silent_targets = array(
        POP_TARGET_QUICKVIEW,
        POP_TARGET_NAVIGATOR,
    );
    if (in_array(\PoP\Root\App::getState('target'), $silent_targets)) {
        // Always silent for the quickView or the Navigator
        $meta[GD_URLPARAM_SILENTDOCUMENT] = true;
    }

    return $meta;
}
