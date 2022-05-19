<?php

// Set the Main Content Module to be the Main Page Section, and not the entry module
define('POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT', POP_PAGECOMPONENTGROUP_MAINCONTENT);

// Whenever setting any module as main content, also set it, by default, into the PageSection Main Content
\PoP\Root\App::addFilter('\PoP\Application\AbstractMainContentComponentRoutingProcessor:maincontentgroups', 'setMaincontentgroups');
function setMaincontentgroups($groups)
{
    $groups[] = POP_PAGECOMPONENTGROUP_PAGESECTION_MAINCONTENT;
    return $groups;
}
