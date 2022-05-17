<?php
namespace PoP\Application;

abstract class AbstractMainContentRouteModuleProcessor extends \PoP\ModuleRouting\AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {

        // If no group specified, then use the "Content Module" one (initially representing the entry module, and overridable)
        // Is it overridable, so the theme can also set group "Page Section Main Content" in addition
        return \PoP\Root\App::applyFilters(
            '\PoP\Application\AbstractMainContentRouteModuleProcessor:maincontentgroups',
            array(
                POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE,
            )
        );
    }
}
