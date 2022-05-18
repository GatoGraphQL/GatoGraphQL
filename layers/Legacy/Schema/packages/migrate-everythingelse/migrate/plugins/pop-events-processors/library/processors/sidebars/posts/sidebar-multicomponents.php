<?php

class GD_EM_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_EVENT = 'sidebarmulticomponent-event';
    public final const MODULE_SIDEBARMULTICOMPONENT_PASTEVENT = 'sidebarmulticomponent-pastevent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_EVENT],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_PASTEVENT],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_EVENT:
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::MODULE_EM_WIDGETCOMPACT_EVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_PASTEVENT:
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}



