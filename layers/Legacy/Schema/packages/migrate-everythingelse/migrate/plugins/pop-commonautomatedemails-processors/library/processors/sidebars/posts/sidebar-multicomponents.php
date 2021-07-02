<?php

class PoPTheme_Wassup_AE_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST = 'sidebarmulticomponent-automatedemails-post';
    public const MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE = 'sidebarmulticomponent-automatedemails-featuredimage';
    public const MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER = 'sidebarmulticomponent-automatedemails-featuredimagevolunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_POST:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_AE_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_Widgets::class, PoPTheme_Wassup_AE_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGE:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_AUTOMATEDEMAILS_FEATUREDIMAGEVOLUNTEER:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER];
                $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG];
                break;
        }

        return $ret;
    }
}



