<?php

abstract class PoP_Module_QuickviewFrameBottomOptionsPageSectionComponentRoutingProcessorBase extends \PoP\ComponentRouting\AbstractComponentRoutingProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_PAGESECTION_QUICKVIEWFRAMEBOTTOMOPTIONS);
    }
}
