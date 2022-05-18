<?php

abstract class PoP_Module_Processor_SidebarMultiplesBase extends PoP_Module_Processor_MultiplesBase
{
    protected function getInnerSubmodules(array $componentVariation): array
    {
        return array();
    }

    protected function getScreen(array $componentVariation)
    {
        return null;
    }

    protected function getScreengroup(array $componentVariation)
    {
        return null;
    }

    protected function getPermanentSubmodules(array $componentVariation)
    {

        // Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_SidebarMultiplesBase:modules',
            array(),
            $this->getScreengroup($componentVariation),
            $this->getScreen($componentVariation),
            $componentVariation
        );
    }
    
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        // Add the corresponding blocks
        if ($modules = $this->getInnerSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        
        // Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
        if ($modules = $this->getPermanentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }

        return $ret;
    }
}
