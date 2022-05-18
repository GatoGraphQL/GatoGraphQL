<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_EM_AE_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO = 'em-widgetcompact-automatedemails-eventinfo';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS];
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::class, PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::MODULE_QUICKLINKGROUP_EVENTBOTTOM];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $componentVariation, array &$props)
    {
        $titles = array(
            self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );

        return $titles[$componentVariation[1]] ?? null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => 'fa-calendar',
        );

        return $fontawesomes[$componentVariation[1]] ?? null;
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'list-group list-group-sm';
        }

        return parent::getBodyClass($componentVariation, $props);
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'pop-hide-empty list-group-item';
        }

        return parent::getItemWrapper($componentVariation, $props);
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:
                return 'panel panel-default panel-sm';
        }

        return parent::getWidgetClass($componentVariation, $props);
    }
}


