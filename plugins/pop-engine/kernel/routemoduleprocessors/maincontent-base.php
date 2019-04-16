<?php
namespace PoP\Engine;

abstract class MainContentRouteModuleProcessorBase extends RouteModuleProcessorBase
{
    public function getGroups()
    {

        // If no group specified, then use the "Content Module" one (initially representing the entry module, and overridable)
        // Is it overridable, so the theme can also set group "Page Section Main Content" in addition
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            '\PoP\Engine\MainContentRouteModuleProcessorBase:maincontentgroups',
            array(
                POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE,
            )
        );
    }
}
