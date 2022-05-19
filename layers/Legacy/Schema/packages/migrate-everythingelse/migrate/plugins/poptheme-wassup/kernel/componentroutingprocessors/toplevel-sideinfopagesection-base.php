<?php

abstract class PoP_Module_SideInfoPageSectionTopLevelComponentRoutingProcessorBase extends \PoP\ComponentRouting\AbstractComponentRoutingProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGECOMPONENTGROUP_TOPLEVEL_SIDEINFOPAGESECTION);
    }
}
