<?php

abstract class PoP_Module_ContentPageSectionTopLevelRouteModuleProcessorBase extends \PoP\ModuleRouting\AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
    }
}
