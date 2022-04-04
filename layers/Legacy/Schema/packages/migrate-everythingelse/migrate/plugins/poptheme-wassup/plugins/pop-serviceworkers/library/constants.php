<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

\PoP\Root\App::addFilter('pop_sw_main_pagesection_container_ids', popthemeWassupSwMainPagesectionContainerIds(...));
function popthemeWassupSwMainPagesectionContainerIds($pagesection_container_ids)
{

    // PageSections where the message "Please refresh your content" for stale JSON requests will be shown
    $pagesection_container_ids[] = POP_MODULEID_PAGESECTIONCONTAINERID_BODY;
    $pagesection_container_ids[] = POP_MODULEID_PAGESECTIONCONTAINERID_ADDONS;
    return $pagesection_container_ids;
}
