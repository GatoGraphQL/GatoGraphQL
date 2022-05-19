<?php

class GD_EM_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEW_EVENT = 'layout-fullview-event';
    public final const COMPONENT_LAYOUT_FULLVIEW_PASTEVENT = 'layout-fullview-pastevent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLVIEW_EVENT],
            [self::class, self::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],
        );
    }

    public function getFooterSubcomponents(array $component)
    {
        $ret = parent::getFooterSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLVIEW_EVENT:
            case self::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                // Allow plugins to hook in layouts
                $ret = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomFullViewLayouts:footer-modules',
                    $ret,
                    $component
                );
                break;
        }

        return $ret;
    }

    public function getSidebarSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLVIEW_EVENT:
            case self::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLVIEW_EVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT],
                    self::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT => [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT],
                );

                return $sidebars[$component[1]];
        }

        return parent::getSidebarSubcomponent($component);
    }
}



