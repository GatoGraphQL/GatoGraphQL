<?php

abstract class PoP_Module_QuickviewFrameTopOptionsPageSectionRouteModuleProcessorBase extends \PoP\ComponentRouting\AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_PAGESECTION_QUICKVIEWFRAMETOPOPTIONS);
    }
}
