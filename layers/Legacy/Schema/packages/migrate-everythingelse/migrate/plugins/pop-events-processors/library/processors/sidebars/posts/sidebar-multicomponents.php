<?php

class GD_EM_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_SIDEBARMULTICOMPONENT_EVENT = 'sidebarmulticomponent-event';
    public final const COMPONENT_SIDEBARMULTICOMPONENT_PASTEVENT = 'sidebarmulticomponent-pastevent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_EVENT],
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_PASTEVENT],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_SIDEBARMULTICOMPONENT_EVENT:
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::COMPONENT_EM_WIDGETCOMPACT_EVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::COMPONENT_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGETCOMPACT_POST_AUTHORS];
                break;

            case self::COMPONENT_SIDEBARMULTICOMPONENT_PASTEVENT:
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::COMPONENT_EM_WIDGETCOMPACT_PASTEVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::COMPONENT_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}



