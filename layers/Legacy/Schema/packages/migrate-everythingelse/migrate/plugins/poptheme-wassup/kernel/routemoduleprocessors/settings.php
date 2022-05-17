<?php

// Set the Main Content Module22222 to be the Main Page Section, and not the entry module
define('POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE', POP_PAGEMODULEGROUP_MAINCONTENT);

// Whenever setting any module as main content, also set it, by default, into the PageSection Main Content
\PoP\Root\App::addFilter('\PoP\Application\AbstractMainContentRouteModuleProcessor:maincontentgroups', 'setMaincontentgroups');
function setMaincontentgroups($groups)
{
    $groups[] = POP_PAGEMODULEGROUP_PAGESECTION_MAINCONTENT;
    return $groups;
}
