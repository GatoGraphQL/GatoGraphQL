<?php

abstract class PoP_Module_SideInfoPageSectionTopLevelRouteModuleProcessorBase extends \PoP\ComponentRouting\AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_TOPLEVEL_SIDEINFOPAGESECTION);
    }
}
