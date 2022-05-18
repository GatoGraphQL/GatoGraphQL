<?php

abstract class PoP_Module_SideInfoFrameOptionsPageSectionComponentRoutingProcessorBase extends \PoP\ComponentRouting\AbstractComponentRoutingProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOFRAMEOPTIONS);
    }
}
