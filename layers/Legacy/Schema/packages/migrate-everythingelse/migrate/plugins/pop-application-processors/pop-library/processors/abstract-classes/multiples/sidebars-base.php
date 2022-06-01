<?php

abstract class PoP_Module_Processor_SidebarMultiplesBase extends PoP_Module_Processor_MultiplesBase
{
    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    protected function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    protected function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    protected function getPermanentSubcomponents(\PoP\ComponentModel\Component\Component $component)
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
    
    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        // Add the corresponding blocks
        if ($components = $this->getInnerSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $components
            );
        }
        
        // Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
        if ($components = $this->getPermanentSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $components
            );
        }

        return $ret;
    }
}
