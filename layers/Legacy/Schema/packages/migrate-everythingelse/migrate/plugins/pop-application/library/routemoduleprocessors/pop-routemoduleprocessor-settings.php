<?php
use PoP\ModuleRouting\ModuleRoutingGroups;

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popEngineDefinePagemodulegroupContentModule'
);
function popEngineDefinePagemodulegroupContentModule()
{
    // the "Main Content Module" group initially represents the entry module, but this is overridable (eg: by the theme, setting it to be the Main PageSection module)
    if (!defined('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE')) {
        define('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE', ModuleRoutingGroups::ENTRYMODULE);
    }
}
