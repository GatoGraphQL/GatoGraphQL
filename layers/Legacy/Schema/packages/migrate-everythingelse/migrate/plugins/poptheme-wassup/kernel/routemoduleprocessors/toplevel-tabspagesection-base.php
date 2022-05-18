<?php

abstract class PoP_Module_TabsPageSectionTopLevelRouteModuleProcessorBase extends \PoP\ComponentRouting\AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_TOPLEVEL_TABSPAGESECTION);
    }
}
