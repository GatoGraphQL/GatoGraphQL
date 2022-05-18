<?php

abstract class PoP_Module_Processor_SidebarMultiplesBase extends PoP_Module_Processor_MultiplesBase
{
    protected function getInnerSubmodules(array $component): array
    {
        return array();
    }

    protected function getScreen(array $component)
    {
        return null;
    }

    protected function getScreengroup(array $component)
    {
        return null;
    }

    protected function getPermanentSubmodules(array $component)
    {

        // Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_SidebarMultiplesBase:modules',
            array(),
            $this->getScreengroup($component),
            $this->getScreen($component),
            $component
        );
    }
    
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        // Add the corresponding blocks
        if ($components = $this->getInnerSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $components
            );
        }
        
        // Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
        if ($components = $this->getPermanentSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $components
            );
        }

        return $ret;
    }
}
