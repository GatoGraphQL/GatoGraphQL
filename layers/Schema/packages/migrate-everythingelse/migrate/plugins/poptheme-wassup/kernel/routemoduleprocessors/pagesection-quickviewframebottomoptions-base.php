<?php

abstract class PoP_Module_QuickviewFrameBottomOptionsPageSectionRouteModuleProcessorBase extends \PoP\ModuleRouting\AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_PAGESECTION_QUICKVIEWFRAMEBOTTOMOPTIONS);
    }
}
