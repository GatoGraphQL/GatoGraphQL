<?php

abstract class PoP_Module_TabsPageSectionTopLevelComponentRoutingProcessorBase extends \PoP\ComponentRouting\AbstractComponentRoutingProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array(POP_PAGEMODULEGROUP_TOPLEVEL_TABSPAGESECTION);
    }
}
