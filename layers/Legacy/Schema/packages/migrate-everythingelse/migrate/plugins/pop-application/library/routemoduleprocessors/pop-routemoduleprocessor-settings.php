<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\ModuleRouting\ModuleRoutingGroups;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popEngineDefinePagemodulegroupContentModule'
);
function popEngineDefinePagemodulegroupContentModule()
{
    // the "Main Content Module" group initially represents the entry module, but this is overridable (eg: by the theme, setting it to be the Main PageSection module)
    if (!defined('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE')) {
        define('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE', ModuleRoutingGroups::ENTRYMODULE);
    }
}
