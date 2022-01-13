<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Add the 'members' tab for the Organizations author page
 */
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_SubMenus:routes', 'gdUreProfileCreateprofilesLinks');
function gdUreProfileCreateprofilesLinks($routes)
{
    $routes[POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL] = array();
    $routes[POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION] = array();

    return $routes;
}
