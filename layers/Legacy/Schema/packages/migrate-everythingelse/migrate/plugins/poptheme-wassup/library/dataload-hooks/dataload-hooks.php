<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

HooksAPIFacade::getInstance()->addFilter('PoPWebPlatform_Engine:request-meta', 'addToplevelVarsSilent');
function addToplevelVarsSilent($meta)
{
    $vars = ApplicationState::getVars();
        
    // Silent document? (Opposite to Update the browser URL and Title?)
    $silent_targets = array(
        POP_TARGET_QUICKVIEW,
        POP_TARGET_NAVIGATOR,
    );
    if (in_array($vars['target'], $silent_targets)) {
        // Always silent for the quickView or the Navigator
        $meta[GD_URLPARAM_SILENTDOCUMENT] = true;
    }

    return $meta;
}
