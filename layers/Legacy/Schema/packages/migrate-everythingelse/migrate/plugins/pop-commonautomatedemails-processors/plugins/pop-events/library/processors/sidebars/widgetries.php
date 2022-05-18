<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_EM_AE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO = 'em-widgetcompact-automatedemails-eventinfo';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS];
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::class, PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::COMPONENT_QUICKLINKGROUP_EVENTBOTTOM];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => 'fa-calendar',
        );

        return $fontawesomes[$component[1]] ?? null;
    }
    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($component, $props);
    }
}


