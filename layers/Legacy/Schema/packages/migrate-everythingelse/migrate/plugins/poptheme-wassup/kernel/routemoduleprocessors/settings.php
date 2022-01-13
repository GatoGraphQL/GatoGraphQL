<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Set the Main Content Module to be the Main Page Section, and not the entry module
define('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE', POP_PAGEMODULEGROUP_MAINCONTENT);

// Whenever setting any module as main content, also set it, by default, into the PageSection Main Content
HooksAPIFacade::getInstance()->addFilter('\PoP\Application\AbstractMainContentRouteModuleProcessor:maincontentgroups', 'setMaincontentgroups');
function setMaincontentgroups($groups)
{
    $groups[] = POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT;
    return $groups;
}
