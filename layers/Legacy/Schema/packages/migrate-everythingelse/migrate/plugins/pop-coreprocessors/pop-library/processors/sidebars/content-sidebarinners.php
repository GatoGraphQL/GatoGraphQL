<?php

class PoP_Module_Processor_ContentSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_SIDEBARINNER_CONTENT_HORIZONTAL = 'contentsidebarinner-horizontal';
    public final const COMPONENT_SIDEBARINNER_CONTENT_VERTICAL = 'contentsidebarinner-vertical';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIDEBARINNER_CONTENT_HORIZONTAL],
            [self::class, self::COMPONENT_SIDEBARINNER_CONTENT_VERTICAL],
        );
    }

    public function getWrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SIDEBARINNER_CONTENT_HORIZONTAL:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }

    public function getWidgetwrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SIDEBARINNER_CONTENT_HORIZONTAL:
                return 'col-xsm-4';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



