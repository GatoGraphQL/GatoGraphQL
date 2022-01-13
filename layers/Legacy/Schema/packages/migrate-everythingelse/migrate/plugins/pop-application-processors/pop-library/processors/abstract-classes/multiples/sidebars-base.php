<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

abstract class PoP_Module_Processor_SidebarMultiplesBase extends PoP_Module_Processor_MultiplesBase
{
    protected function getInnerSubmodules(array $module): array
    {
        return array();
    }

    protected function getScreen(array $module)
    {
        return null;
    }

    protected function getScreengroup(array $module)
    {
        return null;
    }

    protected function getPermanentSubmodules(array $module)
    {

        // Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_Module_Processor_SidebarMultiplesBase:modules',
            array(),
            $this->getScreengroup($module),
            $this->getScreen($module),
            $module
        );
    }
    
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        // Add the corresponding blocks
        if ($modules = $this->getInnerSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        
        // Allow to add the Trending Tags/Events Calendar at the bottom of the sideinfo
        if ($modules = $this->getPermanentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }

        return $ret;
    }
}
