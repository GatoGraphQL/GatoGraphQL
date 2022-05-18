<?php

class PoP_Module_Processor_CustomHorizontalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC = 'horizontal-sidebarinner-author-generic';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;
        }

        return $ret;
    }

    public function getWrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                return 'col-xsm-4';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



