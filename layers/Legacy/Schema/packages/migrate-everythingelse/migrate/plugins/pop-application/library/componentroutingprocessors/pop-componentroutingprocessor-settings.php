<?php
use PoP\ComponentRouting\ComponentRoutingGroups;

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popEngineDefinePagemodulegroupContentModule'
);
function popEngineDefinePagemodulegroupContentModule()
{
    // the "Main Content Module" group initially represents the entry component, but this is overridable (eg: by the theme, setting it to be the Main PageSection component)
    if (!defined('POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT')) {
        define('POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT', ComponentRoutingGroups::ENTRYCOMPONENT);
    }
}
